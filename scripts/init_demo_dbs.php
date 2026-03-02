<?php
declare(strict_types=1);

require __DIR__ . '/../cryptage.php';

/**
 * Creates demo SQLite databases used by the app when local DB files are missing.
 * Existing DB files are kept by default. Set RESET_DEMO_DB=1 to recreate them.
 */
function main(): void
{
    $forceReset = getenv('RESET_DEMO_DB') === '1';
    $bddDir = getenv('APP_BDD_DIR') ?: (__DIR__ . '/../bdd');

    if (!is_dir($bddDir) && !mkdir($bddDir, 0775, true) && !is_dir($bddDir)) {
        fwrite(STDERR, "Cannot create DB directory: {$bddDir}\n");
        exit(1);
    }

    $baseDbPath = rtrim($bddDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'base.db';
    $userDbPath = rtrim($bddDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'user.db';

    if ($forceReset) {
        @unlink($baseDbPath);
        @unlink($userDbPath);
    }

    initBaseDb($baseDbPath);
    initUserDb($userDbPath);

    echo "Demo databases are ready in: {$bddDir}\n";
}

function initBaseDb(string $dbPath): void
{
    $db = new SQLite3($dbPath);
    $db->exec('PRAGMA foreign_keys = ON;');

    $schema = [
        'CREATE TABLE IF NOT EXISTS EB_INB_F (
            BEN_NIR_IDT TEXT NOT NULL PRIMARY KEY,
            BEN_NAI_ANN TEXT NOT NULL,
            BEN_RES_DPT TEXT NOT NULL,
            BEN_SEX_COD INTEGER NOT NULL,
            BEN_DCD_AME TEXT NOT NULL,
            CHECK((length(BEN_NIR_IDT) = 17) OR (length(BEN_NIR_IDT) = 16)),
            CHECK(length(BEN_NAI_ANN) = 4),
            CHECK(length(BEN_RES_DPT) = 3),
            CHECK(BEN_SEX_COD = 1 OR BEN_SEX_COD = 2),
            CHECK((length(BEN_DCD_AME) = 6) OR (length(BEN_DCD_AME) = 0))
        );',
        'CREATE TABLE IF NOT EXISTS IR_ALD_V (
            ALD_030_COD INTEGER NOT NULL PRIMARY KEY,
            ALD_030_LIB TEXT NOT NULL,
            CHECK(ALD_030_COD >= 0 AND ALD_030_COD <= 99),
            CHECK(length(ALD_030_LIB) <= 130)
        );',
        'CREATE TABLE IF NOT EXISTS IR_CIM_V (
            CIM_COD TEXT NOT NULL PRIMARY KEY,
            CIM_LIB TEXT NOT NULL,
            ALD_030_COD INTEGER NOT NULL,
            CHECK(length(CIM_COD) <= 6),
            CHECK(length(CIM_LIB) <= 255),
            CHECK(ALD_030_COD >= 0 AND ALD_030_COD <= 99),
            FOREIGN KEY(ALD_030_COD) REFERENCES IR_ALD_V(ALD_030_COD) ON DELETE CASCADE
        );',
        'CREATE TABLE IF NOT EXISTS IR_NAT_V (
            PRS_NAT INTEGER NOT NULL PRIMARY KEY,
            PRS_NAT_LIB TEXT NOT NULL,
            CHECK((PRS_NAT >= 1000 AND PRS_NAT <= 9999) OR PRS_NAT = 0),
            CHECK(length(PRS_NAT_LIB) <= 150)
        );',
        'CREATE TABLE IF NOT EXISTS EB_IMB_R (
            BEN_NIR_IDT TEXT NOT NULL,
            IMB_ALD_NUM INTEGER NOT NULL,
            IMB_ALD_DTD DATE NOT NULL,
            IMB_ALD_DTF DATE NOT NULL,
            IMB_ETM_NAT INTEGER NOT NULL,
            MED_MTF_COD TEXT NOT NULL,
            FOREIGN KEY(BEN_NIR_IDT) REFERENCES EB_INB_F(BEN_NIR_IDT) ON DELETE CASCADE,
            FOREIGN KEY(MED_MTF_COD) REFERENCES IR_CIM_V(CIM_COD) ON DELETE CASCADE,
            FOREIGN KEY(IMB_ALD_NUM) REFERENCES IR_ALD_V(ALD_030_COD) ON DELETE CASCADE
        );',
        'CREATE TABLE IF NOT EXISTS ES_PRS_F (
            CLE_TEC_JNT TEXT NOT NULL PRIMARY KEY,
            BEN_NIR_IDT TEXT NOT NULL,
            EXE_SOI_DTD DATE NOT NULL,
            EXE_SOI_DTF DATE NOT NULL,
            PFS_PRE_CRY TEXT NOT NULL,
            PRS_NAT_REF INTEGER,
            FLX_DIS_DTD DATE NOT NULL,
            PSE_ACT_SPE INTEGER NOT NULL,
            BEN_CMU_TOP TEXT NOT NULL,
            PRE_PRE_DTD DATE NOT NULL,
            PRS_ACT_QTE INTEGER NOT NULL,
            CHECK(length(CLE_TEC_JNT) <= 77 AND length(CLE_TEC_JNT) >= 70),
            CHECK(length(PFS_PRE_CRY) <= 32 AND length(PFS_PRE_CRY) >= 25),
            CHECK(length(BEN_CMU_TOP) = 1),
            CHECK(PRS_ACT_QTE = 1 OR PRS_ACT_QTE = 0),
            FOREIGN KEY(BEN_NIR_IDT) REFERENCES EB_INB_F(BEN_NIR_IDT) ON DELETE CASCADE,
            FOREIGN KEY(PRS_NAT_REF) REFERENCES IR_NAT_V(PRS_NAT) ON DELETE CASCADE
        );',
    ];

    foreach ($schema as $sql) {
        if (!$db->exec($sql)) {
            fail($db, 'Schema creation failed');
        }
    }

    // Seed lookups
    $db->exec("INSERT OR IGNORE INTO IR_ALD_V(ALD_030_COD, ALD_030_LIB) VALUES (19, 'ALD demo')");
    $db->exec("INSERT OR IGNORE INTO IR_CIM_V(CIM_COD, CIM_LIB, ALD_030_COD) VALUES ('D695', 'CIM demo', 19)");
    $db->exec("INSERT OR IGNORE INTO IR_NAT_V(PRS_NAT, PRS_NAT_LIB) VALUES (1111, 'Nature demo')");

    // Seed one beneficiary
    $db->exec("
        INSERT OR IGNORE INTO EB_INB_F(BEN_NIR_IDT, BEN_NAI_ANN, BEN_RES_DPT, BEN_SEX_COD, BEN_DCD_AME)
        VALUES ('19778802069526556', '1988', '062', 2, '')
    ");

    // Seed one affectation
    $db->exec("
        INSERT OR IGNORE INTO EB_IMB_R(BEN_NIR_IDT, IMB_ALD_NUM, IMB_ALD_DTD, IMB_ALD_DTF, IMB_ETM_NAT, MED_MTF_COD)
        VALUES ('19778802069526556', 19, '2020-01-01', '2099-01-01', 41, 'D695')
    ");

    // Seed one prestation
    $db->exec("
        INSERT OR IGNORE INTO ES_PRS_F(
            CLE_TEC_JNT, BEN_NIR_IDT, EXE_SOI_DTD, EXE_SOI_DTF, PFS_PRE_CRY, PRS_NAT_REF,
            FLX_DIS_DTD, PSE_ACT_SPE, BEN_CMU_TOP, PRE_PRE_DTD, PRS_ACT_QTE
        ) VALUES (
            '1111111111111111111111111111111111111111111111111111111111111111111111',
            '19778802069526556',
            '2021-01-10',
            '2021-01-12',
            'ABCDEFGHIJKLMNOPQRSTUVWXY12345',
            1111,
            '2021-02-01',
            12345678,
            '0',
            '2021-01-01',
            1
        )
    ");

    if ($db->lastErrorCode() !== 0) {
        fail($db, 'Base seed failed');
    }

    $db->close();
}

function initUserDb(string $dbPath): void
{
    $db = new SQLite3($dbPath);

    if (!$db->exec('CREATE TABLE IF NOT EXISTS user (login TEXT PRIMARY KEY, password TEXT NOT NULL);')) {
        fail($db, 'User schema creation failed');
    }

    // Clean invalid rows that can block deterministic demo login behavior.
    $db->exec("DELETE FROM user WHERE COALESCE(CAST(login AS TEXT), '') = ''");

    $adminExists = (int)$db->querySingle("SELECT COUNT(*) FROM user WHERE login = 'admin'");
    if ($adminExists === 0) {
        $password = SQLite3::escapeString(crypte('admin123'));

        $insertWorked = $db->exec(
            "INSERT INTO user(name, login, password) VALUES ('admin', 'admin', '{$password}')"
        );
        if (!$insertWorked) {
            // Fallback for schema without "name" column.
            $insertWorked = $db->exec(
                "INSERT INTO user(login, password) VALUES ('admin', '{$password}')"
            );
        }

        if (!$insertWorked) {
            fail($db, 'User seed failed');
        }

        // Some legacy DBs have triggers that overwrite login on insert.
        $db->exec("
            UPDATE user
            SET login = 'admin'
            WHERE rowid = (
                SELECT rowid FROM user WHERE password = '{$password}' ORDER BY rowid DESC LIMIT 1
            )
        ");
    }

    $db->close();
}

function fail(SQLite3 $db, string $message): void
{
    fwrite(STDERR, $message . ': ' . $db->lastErrorMsg() . "\n");
    exit(1);
}

main();
