var url_string = window.location.href
var url = new URL(url_string);
var c = url.searchParams.get("page");

function parseResponse(response){
    if (response === null || response === undefined) return {};
    if (typeof response === "object") return response;
    if (typeof response === "string"){
        try {
            return JSON.parse(response);
        } catch (e){
            return {};
        }
    }
    return {};
}
    $.ajax({
        method:"post",
        url:"../php/select/selectAff.php",
        data: {off:parseInt(c-1)*10},
        success: function(response){
            let table = document.createElement("table");
            let tr = document.createElement("tr");
            tr.classList.add("titleTblEv");
            let data = document.createElement('td');
            data.innerHTML = "BEN";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "IMB-NUM";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "IMB-DTD";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "IMB-DTF";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "IMB-E";
            tr.appendChild(data);
            data = document.createElement('td');
            data.innerHTML = "MED";
            tr.appendChild(data);
            table.appendChild(tr);
            let tab = parseResponse(response);
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

    /* Lorsqu'on ajoute */
$(document).ready(function(){
    $(document).on('submit','#formAddAffection',function(e){
        e.preventDefault();

        let elm = document.getElementsByClassName('FK');
        for(let i = 0;i<elm.length;i++) elm[i].style.backgroundColor = "";
        if(document.getElementById('DD')) document.getElementById('DD').style.backgroundColor = "";
        if(document.getElementById('DF')) document.getElementById('DF').style.backgroundColor = "";

        $.ajax({
            method:"POST",
            url: "../php/ajout/ajouterAffection.php",
            data:$(this).serialize(),
            dataType:"json",
            success: function(response){
                let pb = parseResponse(response);
                if (Object.keys(pb).length === 0){
                    alert("Erreur technique: reponse invalide du serveur.");
                    return;
                }

                if (pb['FK']){
                    let elm = document.getElementsByClassName('FK');
                    for(let i = 0;i<elm.length;i++) elm[i].style.backgroundColor = "red";
                }
                if(pb['DD']) document.getElementById('DD').style.backgroundColor = "red";
                if(pb['DF']) document.getElementById('DF').style.backgroundColor = "red";

                let res = true;
                for (let e in pb) res = res && !pb[e];

                if(res){
                    let l = document.getElementsByClassName("addBen")[0].getElementsByTagName("input");
                    for(let i = 0;i<l.length - 1;i++){
                        l[i].value = "";
                    }
                    alert("Affectation ajoutee avec succes !");
                } else {
                    alert("Ajout refuse: corrige les champs en rouge (FK/date).");
                }
            },
            error: function(){
                alert("Erreur reseau/serveur pendant l'ajout de l'affectation.");
            }
        });
    });
})

/* Tableau initial */
    $(document).ready(function(){
        var lignePerPage = 10;
        var valueBen;
        var valueMed;
        for(var i = 0; i<lignePerPage;i++){
            var elm = document.getElementById(i+"BEN");
            if (elm != null) {
                elm.ben = elm.childNodes[0].firstChild.nodeValue;
                elm.med = elm.childNodes[5].firstChild.nodeValue;
            }
            if (elm != null) document.getElementById(i+"BEN").addEventListener("click",function(){
                if(this.ben == valueBen && valueMed == this.med){
                    resetColor();
                    valueBen = 0;
                    valueMed = -1;
                }
                else{
                    resetColor();
                    this.style.color = "white";
                    this.style.backgroundColor = "blue";
                    valueBen = this.ben;
                    valueMed = this.med;
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

                        
                        //recuperer les valeurs de l'ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©ment que l'on souhaite modifier
                        var affectationID = saveBtn.childNodes[0].firstChild.nodeValue;
                        var affectationALD = saveBtn.childNodes[1].firstChild.nodeValue;
                        var affectationDD = saveBtn.childNodes[2].firstChild.nodeValue;
                        var affectationSDF = saveBtn.childNodes[3].firstChild.nodeValue;
                        var affectationME = saveBtn.childNodes[4].firstChild.nodeValue;
                        var affectationMMOP = saveBtn.childNodes[5].firstChild.nodeValue;

                        var res;
                        var resSDF;
                        if(affectationSDF.replace("/\s/g","") == "  "){
                            resSDF = "  ";
                        }
                        else{
                            affectationDD = new Date(affectationDD);
                            res = affectationDD.toISOString().slice(0,10);
                        }


                        if(affectationSDF.replace("/\s/g","") == " "){
                            resSDF = "  ";
                        }
                        else{
                            affectationSDF = new Date(affectationSDF);
                            resSDF = affectationSDF.toISOString().slice(0,10);
                        }

                        //remplir les value
                        document.getElementById("BEN_NIR_IDT_UPDATE").value = affectationID;
                        document.getElementById("IMB_ALD_NUM_UPDATE").value = Number(affectationALD);
                        document.getElementById("IMB_ALD_DTD_UPDATE").value = res;
                        document.getElementById("IMB_ALD_DTF_UPDATE").value = resSDF;
                        document.getElementById("IMB_ETM_NAT_UPDATE").value = Number(affectationME);
                        document.getElementById("MED_MTF_COD_UPDATE").value = affectationMMOP;

                        $(document).on('submit','#formBenUpdate',function(e){
                            e.preventDefault();
                            document.getElementById("myModal").style.display ='none';

                            $.ajax({
                                method:"POST",
                                url:"../php/update/updateAffectation.php",
                                data:$(this).serialize(),
                                success: function(response){
                                    
                                    
                                    let arr = parseResponse(response);
                                    
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
                            url: "../php/delete/deleteAffectation.php",
                            data: {ben:valueBen, med:valueMed},
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
    })

    /* Recherche */
    $(document).ready(function(){
        $("#recherche").keyup(function(){
            $.ajax({
                method:'post',
                url:'../php/recherche/searchAffectation.php',
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
                    if(document.getElementsByClassName("searchDivAffectation")[0] != null) document.getElementsByClassName("searchDivAffectation")[0].remove();
                    let tab = parseResponse(response);
                    var div = document.createElement("div");
                    var h1 = document.createElement("h1");
                    h1.innerHTML = "Recherche pour " + $("#recherche").val();
                    div.appendChild(h1);
                    div.className = "searchDivAffectation";
                    var table = document.createElement("table");
                    table.id="searchTableAffectation";

                    //title table
                    let row = document.createElement("tr");
                    row.className = "titleTblEv";
                    let data = document.createElement("td");
                    data.innerHTML = "BEN";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "IMB-NUM";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "IMB-DTD";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "IMB-DTF";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "IMB-E";
                    row.appendChild(data);
                    data = document.createElement("td");
                    data.innerHTML = "MED";
                    row.appendChild(data);
                    table.appendChild(row);

                    //table elements
                    
                    for(let i = 0;i<tab.length;i++){
                        let row = document.createElement("tr");
                        row.id=i+"BENs";
                        row.ben = tab[i]['BEN_NIR_IDT'];
                        row.med = tab[i]['MED_MTF_COD'];
                        var valueBen;
                        var valueMed;
                        row.addEventListener("click",function(){
                            if(this.ben == valueBen){
                                valueBen = 0;
                                valueMed = -1;
                                resetColorSearch();
                            }
                            else{
                                resetColorSearch();
                                valueBen = this.ben;
                                valueMed = this.med;
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
                                        url: "../php/delete/deleteAffectation.php",
                                        data: {ben:valueBen,med:valueMed},
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


                                    //recuperer les valeurs de l'ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©ment que l'on souhaite modifier
                                    var affectationID = ligne.childNodes[0].firstChild.nodeValue;
                                    var affectationALD = ligne.childNodes[1].firstChild.nodeValue;
                                    var affectationDD = ligne.childNodes[2].firstChild.nodeValue;
                                    var affectationSDF = ligne.childNodes[3].firstChild.nodeValue;
                                    var affectationME = ligne.childNodes[4].firstChild.nodeValue;
                                    var affectationMMOP = ligne.childNodes[5].firstChild.nodeValue;




                                    var res;
                                    var resSDF;
                                    if(affectationDD == " "){
                                        res = "  ";
                                    }
                                    else{
                                        affectationDD = new Date(affectationDD);
                                        res = affectationDD.toISOString().slice(0,10);
                                    }

                                    if(affectationSDF == " "){
                                        resSDF = "  ";
                                    }
                                    else{
                                        affectationSDF = new Date(affectationSDF);
                                        resSDF = affectationSDF.toISOString().slice(0,10);
                                    }


                                    //remplir les value
                                    document.getElementById("BEN_NIR_IDT_UPDATE").value = affectationID;
                                    document.getElementById("IMB_ALD_NUM_UPDATE").value = Number(affectationALD);
                                    document.getElementById("IMB_ALD_DTD_UPDATE").value = res;
                                    document.getElementById("IMB_ALD_DTF_UPDATE").value = resSDF;
                                    document.getElementById("IMB_ETM_NAT_UPDATE").value = Number(affectationME);
                                    document.getElementById("MED_MTF_COD_UPDATE").value = affectationMMOP;



                                    $(document).on('submit','#formBenUpdate',function(e){
                                        e.preventDefault();
                                        document.getElementById("myModal").style.display ='none';

                                        $.ajax({
                                            method:"POST",
                                            url:"../php/update/updateAffectation.php",
                                            data:$(this).serialize(),
                                            success: function(response){
                                                
                                                let arr = parseResponse(response);
                                                for(let i = 0;i<5;i++) ligne.childNodes[i+1].firstChild.nodeValue = arr[i];
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
                                document.getElementsByClassName('searchDivAffectation')[0].appendChild(btn);
                                document.getElementsByClassName('searchDivAffectation')[0].appendChild(edit);


                            }
                            
                        });
                        
                        for(const val in tab[i]){
                          let data = document.createElement('td');
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
                        if(document.getElementsByClassName("searchDivAffectation")[0] != null) document.getElementsByClassName("searchDivAffectation")[0].remove();
                    }
                }
            })
        })
    });


