/* Fonction qui charge le tableau */
var url_string = window.location.href
var url = new URL(url_string);
var c = url.searchParams.get("page");
    $.ajax({
        method:"post",
        url:"../php/select/selectPres.php",
        data: {off:parseInt(c-1)*10},
        success: function(response){
            let table = document.createElement("table");
            let tr = document.createElement("tr");
            tr.classList.add("titleTblEv");
            let data = document.createElement('td');
            data.innerHTML = "CLE_TEC_JNT";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "BEN_NIR_IDT";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "EXE_SOI_DTD";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "EXE_SOI_DTF";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "PFS_PRE_CRY";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "PRS_NAT_REF";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "FLX_DIS_DTD";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "PSE_ACT_SPE";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "BEN_CMU_TOP";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "PRE_PRE_DTD";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "PRS_ACT_QTE";
            tr.appendChild(data);
            table.appendChild(tr);
            let tab = JSON.parse(response);
            for(let i = 0;i<tab.length;i++){
                let newR = document.createElement("tr");
                newR.id = i+"BEN";
                for(const val in tab[i]){
                    data = document.createElement('td');
                    if(tab[i][val] == 0) { // au cas où la data = 0 pour éviter bloc vide
                        data.innerHTML = 0;
                        newR.appendChild(data);
                    }
                    else{
                        data.innerHTML = tab[i][val] == '' ? ' ' : tab[i][val];
                        newR.appendChild(data);
                    }


                }
                table.appendChild(newR);
            }
            document.getElementsByClassName('formulaireListPresta')[0].insertBefore(table,document.getElementsByClassName('numerotation')[0]);
        }
    });

/* Quand on ajoute une prestation */
$(document).ready(function(){
    $(document).on('submit','#formAddPrestation',function(e){
        e.preventDefault();
        $.ajax({
            method:"POST",
            url: "../php/ajout/ajouterPrestation.php",
            data:$(this).serialize(),
            success: function(response){

                //var tab = response.split('|');
                
                let tab = JSON.parse(response);

                let size = Object.keys(tab).length;

                if(size == 2){
                    document.getElementById("dD").style.backgroundColor = "red";
                    document.getElementById("dF").style.backgroundColor = "red";
                }
                else{
                    for(const val in tab){
                        if(!tab[val]) document.getElementById(val).style.backgroundColor = "red";
                    }
                }
                let l = document.getElementsByClassName("addPres")[0].getElementsByTagName("input");
                for(let i = 0;i<l.length - 1;i++){
                    l[i].value = "";
                }
                let res = true;
                for(let v in tab) res = res && tab[v];
                if(res) alert('Préstation ajoutée avec succès !');
            }
        });
    });
})


/* Tableau initial */
$(document).ready(function(){
    var lignePerPage = 10;
    var valueCtj;
    for(var i = 0; i<lignePerPage;i++){
        var elm = document.getElementById(i+"BEN");
        if (elm != null) {
            elm.ctj = elm.childNodes[0].firstChild.nodeValue;
        }
        if (elm != null) document.getElementById(i+"BEN").addEventListener("click",function(){
            if(this.ctj == valueCtj){
                resetColor();
                valueCtj = 0;
            }
            else{
                resetColor();
                this.style.color = "white";
                this.style.backgroundColor = "blue";
                valueCtj = this.ctj;
                let btn = document.createElement("button");
                btn.innerHTML = "Supprimer";
                btn.id = "delete";
                var ligne = this;

                let edit = document.createElement("button");
                edit.innerHTML = "Update";
                edit.id = "update";
                $(document).on("click","#update",function(){
                    // le code js quand on appuie sur update

                    // recuperer le modal
                    var modal = document.getElementById("myModal");


                    //recuperer les valeurs de l'élément que l'on souhaite modifier
                    var prestationCLE = ligne.childNodes[0].firstChild.nodeValue;
                    var prestationBEN = ligne.childNodes[1].firstChild.nodeValue;
                    var prestationEDTD = ligne.childNodes[2].firstChild.nodeValue;
                    var prestationEDTF = ligne.childNodes[3].firstChild.nodeValue;
                    var prestationCRY = ligne.childNodes[4].firstChild.nodeValue;
                    var prestationPRS = ligne.childNodes[5].firstChild.nodeValue;
                    var prestationFLX = ligne.childNodes[6].firstChild.nodeValue;
                    var prestationPSE = ligne.childNodes[7].firstChild.nodeValue;
                    var prestationBENC = ligne.childNodes[8].firstChild.nodeValue;
                    var prestationPRE = ligne.childNodes[9].firstChild.nodeValue;
                    var prestationPRSQ = ligne.childNodes[10].firstChild.nodeValue;



                    var resEDTD;
                    var resEDTF;
                    var resFLX;
                    var resPRE;

                    if(prestationEDTD == "  "){
                        resEDTD = "  ";
                    }
                    else{
                        prestationEDTD = new Date(prestationEDTD);
                        resEDTD = prestationEDTD.toISOString().slice(0,10);
                    }

                    if(prestationEDTF == "  "){
                        resEDTF = "  ";
                    }
                    else{
                        prestationEDTF = new Date(prestationEDTF);
                        resEDTF = prestationEDTF.toISOString().slice(0,10);
                    }

                    if(prestationFLX == "  "){
                        resFLX = "  ";
                    }
                    else{
                        prestationFLX = new Date(prestationFLX);
                        resFLX = prestationFLX.toISOString().slice(0,10);
                    }

                    if(prestationPRE == "  "){
                        resPRE = "  ";
                    }
                    else{
                        prestationPRE = new Date(prestationPRE);
                        resPRE = prestationPRE.toISOString().slice(0,10);
                    }

                    //remplir les value
                    document.getElementById("CLE_TEC_JNT_UPDATE").value = prestationCLE;
                    document.getElementById("BEN_NIR_IDT_UPDATE").value = prestationBEN;
                    document.getElementById("EXE_SOI_DTD_UPDATE").value = resEDTD;
                    document.getElementById("EXE_SOI_DTF_UPDATE").value = resEDTF;
                    document.getElementById("PFS_PRE_CRY_UPDATE").value = prestationCRY;
                    document.getElementById("PRS_NAT_REF_UPDATE").value = Number(prestationPRS);
                    document.getElementById("FLX_DIS_DTD_UPDATE").value = resFLX;
                    document.getElementById("PSE_ACT_SPE_UPDATE").value = Number(prestationPSE);
                    document.getElementById("BEN_CMU_TOP_UPDATE").value = prestationBENC;
                    document.getElementById("PRE_PRE_DTD_UPDATE").value = resPRE;
                    document.getElementById("PRS_ACT_QTE_UPDATE").value = Number(prestationPRSQ);

                    $(document).on('submit','#formPreUpdate',function(e){
                        e.preventDefault();
                        document.getElementById("myModal").style.display ='none';

                        $.ajax({
                            method:"POST",
                            url:"../php/update/updatePrestation.php",
                            data:$(this).serialize(),
                            success: function(response){
                                
                                let arr = JSON.parse(response);
                                for(let i = 0;i<11;i++) ligne.childNodes[i].firstChild.nodeValue = arr[i]; // OU METTRE 11?
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
                        url: "../php/delete/deletePrestation.php",
                        data: {ctj:valueCtj},
                        success: function(response){

                            ligne.remove();
                            if (document.getElementById("delete") != null) document.getElementById("delete").remove();
                            if(document.getElementById("update") != null) document.getElementById("update").remove();

                        }
                    });

                });
                document.getElementsByClassName("formulaireListPresta")[0].appendChild(btn);
                document.getElementsByClassName("formulaireListPresta")[0].appendChild(edit);
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
})

/* Recherche */
$(document).ready(function(){
    $("#recherche").keyup(function(){
        $.ajax({
            method:'post',
            url:'../php/recherche/searchPrestation.php',
            data:{id:$("#recherche").val()},
            success:function(response){
                if($("#recherche").val() == ""){
                    document.getElementsByClassName("formulaireListPresta")[0].style.display = "block";
                    document.getElementsByClassName("addPres")[0].style.display = "block";
                }
                else{
                    document.getElementsByClassName("formulaireListPresta")[0].style.display = "none";
                    document.getElementsByClassName("addPres")[0].style.display = "none";
                }
                if(document.getElementsByClassName("searchDivPrestation")[0] != null) document.getElementsByClassName("searchDivPrestation")[0].remove();
                let tab = JSON.parse(response);
                var div = document.createElement("div");
                var h1 = document.createElement("h1");
                h1.innerHTML = "Recherche pour " + $("#recherche").val();
                div.appendChild(h1);
                div.className = "searchDivPrestation";
                var table = document.createElement("table");
                table.id="searchTableAffectation";

                //title table
                let row = document.createElement("tr");
                row.className = "titleTblEv";
                let data = document.createElement("td");
                data.innerHTML = "CLE_TEC_JNT";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "BEN_NIR_IDT";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "EXE_SOI_DTD";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "EXE_SOI_DTF";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "PFS_PRE_CRY";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "PRS_NAT_REF";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "FLX_DIS_DTD";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "PSE_ACT_SPE";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "BEN_CMU_TOP";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "PRE_PRE_DTD";
                row.appendChild(data);
                data = document.createElement("td");
                data.innerHTML = "PRS_ACT_QTE";
                row.appendChild(data);
                table.appendChild(row);
                //table elements
                for(let i = 0;i<tab.length;i++){
                    let row = document.createElement("tr");
                    row.id=i+"BENs";
                    row.ctj = tab[i]["CLE_TEC_JNT"];
                    row.ben = tab[i]["BEN_NIR_IDT"];
                    var valueCtj;
                    var valueBen;
                    row.addEventListener("click",function(){
                        if(this.ctj == valueCtj){
                            valueBen = 0;
                            valueCtj = 0;
                            resetColorSearch();
                        }
                        else{
                            resetColorSearch();
                            valueCtj = this.ctj;
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
                                    url: "../php/delete/deletePrestation.php",
                                    data: {ctj:valueCtj},
                                    success: function(response){
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
                                var prestationCLE = ligne.childNodes[0].firstChild.nodeValue;
                                var prestationBEN = ligne.childNodes[1].firstChild.nodeValue;
                                var prestationEDTD = ligne.childNodes[2].firstChild.nodeValue;
                                var prestationEDTF = ligne.childNodes[3].firstChild.nodeValue;
                                var prestationCRY = ligne.childNodes[4].firstChild.nodeValue;
                                var prestationPRS = ligne.childNodes[5].firstChild.nodeValue;
                                var prestationFLX = ligne.childNodes[6].firstChild.nodeValue;
                                var prestationPSE = ligne.childNodes[7].firstChild.nodeValue;
                                var prestationBENC = ligne.childNodes[8].firstChild.nodeValue;
                                var prestationPRE = ligne.childNodes[9].firstChild.nodeValue;
                                var prestationPRSQ = ligne.childNodes[10].firstChild.nodeValue;


                                var resEDTD;
                                var resEDTF;
                                var resFLX;
                                var resPRE;

                                if(prestationEDTD.replace() == "  "){
                                    resEDTD = "  ";
                                }
                                else{
                                    prestationEDTD = new Date(prestationEDTD);
                                    resEDTD = prestationEDTD.toISOString().slice(0,10);
                                }

                                if(prestationEDTF == "  "){
                                    resEDTF = "  ";
                                }
                                else{
                                    prestationEDTF = new Date(prestationEDTF);
                                    resEDTF = prestationEDTF.toISOString().slice(0,10);
                                }

                                if(prestationFLX == "  "){
                                    resFLX = "  ";
                                }
                                else{
                                    prestationFLX = new Date(prestationFLX);
                                    resFLX = prestationFLX.toISOString().slice(0,10);
                                }

                                if(prestationPRE == "  "){
                                    resPRE = "  ";
                                }
                                else{
                                    prestationPRE = new Date(prestationPRE);
                                    resPRE = prestationPRE.toISOString().slice(0,10);
                                }


                                //remplir les value
                                document.getElementById("CLE_TEC_JNT_UPDATE").value = prestationCLE;
                                document.getElementById("BEN_NIR_IDT_UPDATE").value = prestationBEN;
                                document.getElementById("EXE_SOI_DTD_UPDATE").value = prestationEDTD;
                                document.getElementById("EXE_SOI_DTF_UPDATE").value = prestationEDTF;
                                document.getElementById("PFS_PRE_CRY_UPDATE").value = prestationCRY;
                                document.getElementById("PRS_NAT_REF_UPDATE").value = Number(prestationPRS);
                                document.getElementById("FLX_DIS_DTD_UPDATE").value = prestationFLX;
                                document.getElementById("PSE_ACT_SPE_UPDATE").value = Number(prestationPSE);
                                document.getElementById("BEN_CMU_TOP_UPDATE").value = prestationBENC;
                                document.getElementById("PRE_PRE_DTD_UPDATE").value = prestationPRE;
                                document.getElementById("PRS_ACT_QTE_UPDATE").value = Number(prestationPRSQ);



                                $(document).on('submit','#formPreUpdate',function(e){
                                    e.preventDefault();
                                    document.getElementById("myModal").style.display ='none';

                                    $.ajax({
                                        method:"POST",
                                        url:"../php/update/updatePrestation.php",
                                        data:$(this).serialize(),
                                        success: function(response){
                                            
                                            let arr = JSON.parse(response);
                                            for(let i = 0;i<11;i++) ligne.childNodes[i+1].firstChild.nodeValue = arr[i];
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
                            document.getElementsByClassName('searchDivPrestation')[0].appendChild(btn);
                            document.getElementsByClassName('searchDivPrestation')[0].appendChild(edit);


                        }

                    });
                    for(const val in tab[i]){
                        let data = document.createElement("td");
                        data.innerHTML = tab[i][val] == "" ? " " : tab[i][val];
                        row.appendChild(data);
                    }
                    table.appendChild(row);
                }
                var resetColorSearch = function(){
                    for(let i = 0;i<tab.length;i++){
                        if(document.getElementById(i+"BENs") != null) {
                            document.getElementById(i+"BENs").style.color = 'black';
                            if (i % 2 == 0) document.getElementById(i+"BENs").style.backgroundColor = "white";
                            else document.getElementById(i+"BENs").style.backgroundColor = "rgba(0, 128, 107, 1)";
                        }
                    }
                    $("#update").remove();
                    $("#delete").remove();
                    $(document).off("click","#update");
                    $(document).off("click","#delete");
                }
                div.appendChild(table);
                br = document.createElement("br");
                div.appendChild(br);
                document.body.appendChild(div);
                if(document.getElementsByClassName("formulaireListPresta")[0].style.display == "block"){
                    if(document.getElementsByClassName("searchDivPrestation")[0] != null) document.getElementsByClassName("searchDivPrestation")[0].remove();
                }
            }
        })
    })
});
