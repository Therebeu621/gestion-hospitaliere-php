/* Fonction qui charge le tableau */
var url_string = window.location.href
var url = new URL(url_string);
var c = url.searchParams.get("page");
    $.ajax({
        method:"post",
        url:"../php/select/selectBen.php",
        data: {off:parseInt(c-1)*20},
        success: function(response){
            let table = document.createElement("table");
            let tr = document.createElement("tr");
            tr.classList.add("titleTblEv");
            let data = document.createElement('td');
            data.innerHTML = "ID";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "DATE-N";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "DEPARTEMENT";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "SEX";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "DATE-DC";
            tr.appendChild(data);
            table.appendChild(tr);
            let tab = JSON.parse(response);
            for(let i = 0;i<tab.length;i++){
                let newR = document.createElement("tr");
                newR.id = i+"BEN";
                for(const val in tab[i]){
                    data = document.createElement('td');
                    data.innerHTML = tab[i][val] == '' ? ' ' : tab[i][val];
                    newR.appendChild(data);

                }
                table.appendChild(newR);
            }
            document.getElementsByClassName('formulaireList')[0].insertBefore(table,document.getElementsByClassName('numerotation')[0]);
        }
    });

    /* Quand on ajoute un ben */
    $(document).ready(function(){
        $(document).on('submit','#formBen',function(e){
            e.preventDefault();
            $.ajax({
                method:"POST",
                url: "../php/ajout/ajouterBeneficiaire.php",
                data:$(this).serialize(),
                success: function(response){
                    let tab = JSON.parse(response);
                    for(let e in tab) tab[e] ? document.getElementById(e).style.backgroundColor = "red" : "";
                    let l = document.getElementsByClassName("addBen")[0].getElementsByTagName("input");
                    for(let i = 0;i<l.length - 1;i++){
                        l[i].value = "";
                    }
                    let res = true
                    for(let e in tab) res = res && !tab[e];
                    if(res) alert("Bénéficiaire ajoutée avec succès !");

                }
            });
        });
    })


    /* Tableau initial */
    $(document).ready(function(){
        var lignePerPage = 20;
        var valueBen;
        for(var i = 0; i<lignePerPage;i++){
            var elm = document.getElementById(i+"BEN");
            if (elm != null) elm.ben = elm.childNodes[0].firstChild.nodeValue;

            if (elm != null) document.getElementById(i+"BEN").addEventListener("click",function(){
                if(this.ben == valueBen){
                    resetColor();
                    valueBen = 0;
                }
                else{
                    resetColor();
                    this.style.color = "white";
                    this.style.backgroundColor = "blue";
                    valueBen = this.ben;
                    let btn = document.createElement("button");
                    btn.innerHTML = "Supprimer";
                    btn.id = "delete";
                    var saveBtn = this;

                    let edit = document.createElement("button");
                    edit.innerHTML = "Update";
                    edit.id = "update";
                    $(document).on("click","#update",function(){
                        // le code js quand on appuie sur update

                        // recuperer le modal
                        var modal = document.getElementById("myModal");


                        //recuperer les valeurs de l'élément que l'on souhaite modifier
                        var beneficiaireID = saveBtn.childNodes[0].firstChild.nodeValue;
                        var beneficiaireNAI = saveBtn.childNodes[1].firstChild.nodeValue;
                        var beneficiaireDEP = saveBtn.childNodes[2].firstChild.nodeValue;
                        var beneficiaireSEX = saveBtn.childNodes[3].firstChild.nodeValue;
                        var beneficiaireDCD = saveBtn.childNodes[4].firstChild.nodeValue;

                        //remplir les value
                        document.getElementById("BEN_NIR_IDT_UPDATE").value = beneficiaireID;
                        document.getElementById("BEN_NAI_ANN_UPDATE").value = beneficiaireNAI;
                        document.getElementById("BEN_RES_DPT_UPDATE").value = beneficiaireDEP;
                        document.getElementById("BEN_SEX_COD_UPDATE").value = beneficiaireSEX;
                        document.getElementById("BEN_DCD_AME_UPDATE").value = beneficiaireDCD;



                        $(document).on('submit','#formBenUpdate',function(e){
                            e.preventDefault();
                            document.getElementById("myModal").style.display ='none';

                            $.ajax({
                                method:"POST",
                                url:"../php/update/updateBeneficiaire.php",
                                data:$(this).serialize(),
                                success: function(response){
                                    
                                    let arr = JSON.parse(response);
                                    for(let i = 0;i<4;i++) saveBtn.childNodes[i+1].firstChild.nodeValue = arr[i];
                                    resetColor();
                                }
                            });

                        });

                        modal.style.display = "block";
                        // recuperer la croix
                        var span = document.getElementsByClassName("close")[0];

                        // si il clique sur la croix ca close
                        span.onclick = function() {
                            modal.style.display = "none";
                        }

                        // si il clique ailleur ca close
                        window.onclick = function(event) {
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
                        }
                    });
                    $(document).on("click","#delete",function(){
                        $.ajax({
                            method:"POST",
                            url: "../php/delete/deleteBeneficiaire.php",
                            data: {ben:valueBen},
                            success: function(response){
                                saveBtn.remove();
                                if (document.getElementById("delete") != null) document.getElementById("delete").remove();
                                if(document.getElementById("update") != null) document.getElementById("update").remove();
                            }
                        });

                    });
                    document.getElementsByClassName("formulaireList")[0].appendChild(btn);
                    document.getElementsByClassName("formulaireList")[0].appendChild(edit);
                }

            });
        }

        var resetColor = function(){
            if(document.getElementById("update") != null) document.getElementById("update").remove();
            if(document.getElementById("delete") != null) document.getElementById("delete").remove();
            $(document).off("click","#delete");
            $(document).off("click","#update");


            for(var i = 0; i < lignePerPage;i++){
                if(document.getElementById(i+"BEN") != null){
                    document.getElementById(i+"BEN").style.color ="black";
                    if(i % 2 == 0) document.getElementById(i+"BEN").style.backgroundColor ="white";
                    else document.getElementById(i+"BEN").style.backgroundColor ="rgba(0, 128, 107, 1)";
                }
            }
        }
    });

    /* Recherche */
    $(document).ready(function(){
        $("#recherche").keyup(function(){
            $.ajax({
                method:'post',
                url:'../php/recherche/searchBeneficiaire.php',
                data:{id:$("#recherche").val()},
                success:function(response){
                    
                    if($("#recherche").val() == ""){
                        document.getElementsByClassName("formulaireList")[0].style.display = "block";
                        document.getElementsByClassName("addBen")[0].style.display = "block";
                    }
                    else{
                        document.getElementsByClassName("formulaireList")[0].style.display = "none";
                        document.getElementsByClassName("addBen")[0].style.display = "none";
                    }

                    if(document.getElementsByClassName("searchDiv")[0] != null) document.getElementsByClassName("searchDiv")[0].remove();

                    let tab = JSON.parse(response);
                    var div = document.createElement("div");
                    var h1 = document.createElement("h1");
                    h1.innerHTML = "Recherche pour " + $("#recherche").val();
                    div.appendChild(h1);
                    div.className = "searchDiv";
                    var table = document.createElement("table");
                    table.id="searchTable";

                    //title table
                    let row = document.createElement("tr");
                    row.className = "titleTblEv";
                    let data = document.createElement("td");
                    data.innerHTML = "ID";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "DATE-N";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "DEPARTEMENT";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "SEX";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "DATE-DC";
                    row.appendChild(data);
                    table.appendChild(row);

                    //table elements
                    for(let i = 0;i<tab.length;i++){
                        let row = document.createElement("tr");
                        row.id=parseInt(i)+"BENs";
                        row.ben = tab[i]["BEN_NIR_IDT"];
                        var valueBen;
                        row.addEventListener("click",function(){
                            if(this.ben == valueBen){
                                valueBen = 0;
                                resetColorSearch();
                            }
                            else{
                                resetColorSearch();
                                valueBen = this.ben;
                                var ligne = this;
                                this.style.color = "white";
                                this.style.backgroundColor = "blue";
                                let btn = document.createElement("button");
                                btn.innerHTML = "Supprimer";
                                btn.id = "delete";
                                let edit = document.createElement("button");
                                edit.innerHTML = "Update";
                                edit.id = "update";
                                $(document).on("click","#delete",function(){
                                    $.ajax({
                                        method:"POST",
                                        url: "../php/delete/deleteBeneficiaire.php",
                                        data: {ben:valueBen},
                                        success: function(){
                                            ligne.remove();
                                            if (document.getElementById("delete") != null) document.getElementById("delete").remove();
                                            if(document.getElementById("update") != null) document.getElementById("update").remove();
                                        }
                                    });

                                });

                                $(document).on("click","#update",function(){
                                    // le code js quand on appuie sur update

                                    // recuperer le modal
                                    var modal = document.getElementById("myModal");


                                    //recuperer les valeurs de l'élément que l'on souhaite modifier
                                    var beneficiaireID = ligne.childNodes[0].firstChild.nodeValue;
                                    var beneficiaireNAI = ligne.childNodes[1].firstChild.nodeValue;
                                    var beneficiaireDEP = ligne.childNodes[2].firstChild.nodeValue;
                                    var beneficiaireSEX = ligne.childNodes[3].firstChild.nodeValue;
                                    var beneficiaireDCD = ligne.childNodes[4].firstChild.nodeValue;

                                    //remplir les value
                                    document.getElementById("BEN_NIR_IDT_UPDATE").value = beneficiaireID;
                                    document.getElementById("BEN_NAI_ANN_UPDATE").value = beneficiaireNAI;
                                    document.getElementById("BEN_RES_DPT_UPDATE").value = beneficiaireDEP;
                                    document.getElementById("BEN_SEX_COD_UPDATE").value = beneficiaireSEX;
                                    document.getElementById("BEN_DCD_AME_UPDATE").value = beneficiaireDCD;



                                    $(document).on('submit','#formBenUpdate',function(e){
                                        e.preventDefault();
                                        document.getElementById("myModal").style.display ='none';

                                        $.ajax({
                                            method:"POST",
                                            url:"../php/update/updateBeneficiaire.php",
                                            data:$(this).serialize(),
                                            success: function(response){
                                                let arr = JSON.parse(response);
                                                for(let i = 0;i<4;i++) ligne.childNodes[i+1].firstChild.nodeValue = arr[i];
                                                resetColorSearch();
                                            }
                                        });

                                    });

                                    modal.style.display = "block";
                                    // recuperer la croix
                                    var span = document.getElementsByClassName("close")[0];

                                    // si il clique sur la croix ca close
                                    span.onclick = function() {
                                        modal.style.display = "none";
                                    }

                                    // si il clique ailleur ca close
                                    window.onclick = function(event) {
                                        if (event.target == modal) {
                                            modal.style.display = "none";
                                        }
                                    }
                                });
                                document.getElementsByClassName('searchDiv')[0].appendChild(btn);
                                document.getElementsByClassName('searchDiv')[0].appendChild(edit);

                            }

                        });
                        for(const val in tab[i]){
                          let data = document.createElement("td");
                          data.innerHTML = tab[i][val] == "" ? " " : tab[i][val];
                          row.appendChild(data);
                        }
                        var resetColorSearch = function(){
                            for(let i = 0;i<tab.length;i++){

                                if(document.getElementById(i+"BENs") != null) {
                                    document.getElementById(i + "BENs").style.color = 'black';
                                    if (i % 2 == 0) document.getElementById(i + "BENs").style.backgroundColor = "white";
                                    else document.getElementById(i + "BENs").style.backgroundColor = "rgba(0, 128, 107, 1)";
                                }
                            }
                            $("#update").remove();
                            $("#delete").remove();
                            $(document).off("click","#update");
                            $(document).off("click","#delete");
                        }
                        table.appendChild(row);
                    }
                    div.appendChild(table);
                    br = document.createElement("br");
                    div.appendChild(br);
                    document.body.appendChild(div);
                    if(document.getElementsByClassName("formulaireList")[0].style.display == "block"){
                        if(document.getElementsByClassName("searchDiv")[0] != null) document.getElementsByClassName("searchDiv")[0].remove();
                    }
                }
            })
        })
    }
    );
