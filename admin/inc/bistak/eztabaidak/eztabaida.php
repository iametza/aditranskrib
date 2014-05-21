<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jscolor/jscolor.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.form.min.js"></script>

<script type="text/javascript">
	function verif(){
		var patroi_hutsik = /^\s*$/;

		return (confirm ("Gorde elementua?"));
	}
	
	// segundoak * 10 jaso eta hh:mm:ss formatuko kate bat itzultzen du.
	function egokituDenboraHHMMSSra(denbora) {
		// Segundo kopurua eskuratu
		var segundoak = denbora / 10;
		
		// Segundo kopurua hh:mm:ss formatuko kate bihurtuko dugu.
		var katea = "";
		
		// Zenbat ordu daude segundo horietan?
		var h = Math.floor(segundoak / 3600);
		
		// Itzuliko dugun kateari orduak gehitu
		if (h > 9) {
			katea = h + katea;
		} else {
			katea = katea + '0' + h; 
		}
		
		// Ordu horiek kenduta geratzen diren segundoak
		segundoak = segundoak - h * 3600;
		
		// Zenbat minutu daude geratzen diren segundoetan?
		var m = Math.floor(segundoak / 60);
		
		// Itzuliko dugun kateari minutuak gehitu
		if (m > 9) {
			katea = katea + ':' + m;
		} else {
			katea = katea + ':0' + m;
		}
		
		// Minutu horiek kenduta geratzen diren segundoak
		var s = segundoak - m * 60;
		
		// Itzuliko dugun kateari segundoak gehitu
		if (s > 9) {
			katea = katea + ':' + s;
		} else {
			katea = katea + ':0' + s;
		}
		
		return katea;
	}
	
	$(document).on("click", "#editatu_hizlaria_kolorea", function() {
		
		// Hau gabe scroll egitean kolore-hautatzailea ezkutatu ondoren ezin zen berriz bistaratu.
		document.getElementById("editatu_hizlaria_kolorea").color.showPicker();
	});
	
	$(document).on('shown', '#editatu_hizlaria', function() {
		
		// Hizlaria editatzeko modala bistaratzean edukien scrolla gora eraman
	    $("#editatu_hizlaria .modal-body").scrollTop(0);
		
		$("#editatu_hizlaria .modal-body").unbind("scroll");
		$("#editatu_hizlaria .modal-body").scroll(function() {
			
			// Modalaren barruko scrolla hastean kolore-hautatzailea ezkutatu.
			document.getElementById("editatu_hizlaria_kolorea").color.hidePicker();
		})
	});
	
	$(document).on("click", "#gehitu_hizlaria_botoia", function(event) {
		var id_hizlaria = 0;
		
		// 0ak id berria behar duela adierazten du
		$("#editatu_hizlaria_id").val(id_hizlaria);
		
		// Eztabaidaren id-a gordeko dugu ezkutuko input batean.
		$("#editatu_hizlaria_id_eztabaida").val($("#hidden_id_eztabaida").val());
		
		// Leiho modalaren izenburuan Gehitu hizlaria jarri.
		$("#editatu_hizlaria_izenburua_etiketa").text("Gehitu hizlaria");
		
		// Irudiaren inputa garbitu
		$("#editatu_hizlaria_grafismoa_irudia").val("");
		
		// Aurretik egon daitezkeen Ikusi eta Ezabatu estekak kendu (eta tarteko | ere bai)
		$("#editatu_hizlaria_grafismoa_irudia_ikusi").remove();
		$('#editatu_hizlaria_grafismoa_irudia_banatzailea').remove();
		$("#editatu_hizlaria_grafismoa_irudia_ezabatu").remove();
		
		// Hizkuntza kopuruaren arabera beharrezko fieldset-ak sortu.
		$.post("<?php echo $url_base; ?>editatu_hizlaria", {"id_hizlaria": id_hizlaria}, function(data) {
			console && console.log(data);
			
			// Testuen fieldseta garbitu.
			$("#editatu_hizlaria_fieldset_edukinontzia").empty();
				
			if (data["arrakasta"]) {
				
				for (var i = 0; i < data["hizkuntzak"].length; i++) {
					
					$("#editatu_hizlaria_fieldset_edukinontzia").append("<fieldset data-h_id='" + data["hizkuntzak"][i].h_id + "' id='editatu_hizlaria_fieldset_" + i + "'>" +
							"<legend><strong>Testuak: " + data["hizkuntzak"][i].hizkuntza + "</strong></legend>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "'>Izena:</label>" +
								"<input class='input-xlarge editatu_hizlaria_izena' type='text' id='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "' value='' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_izen_laburra_" + data["hizkuntzak"][i].h_id + "'>Izen laburra:</label>" +
								"<input class='input-xlarge editatu_hizlaria_izen_laburra' type='text' id='editatu_hizlaria_izen_laburra_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_izen_laburra_" + data["hizkuntzak"][i].h_id + "' value='' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "'>Aurrizkia:</label>" +
								"<input class='input-xlarge editatu_hizlaria_aurrizkia' type='text' id='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "' value='' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_gazta_etiketa_" + data["hizkuntzak"][i].h_id + "'>Gazta etiketa:</label>" +
								"<input class='input-xlarge editatu_hizlaria_gazta_etiketa' type='text' id='editatu_hizlaria_gazta_etiketa_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_gazta_etiketa_" + data["hizkuntzak"][i].h_id + "' value='' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_grafismoa_deskribapena_" + data["hizkuntzak"][i].h_id + "'>Grafismoa deskribapena:</label>" +
								"<input class='input-xlarge editatu_hizlaria_grafismoa_deskribapena' type='text' id='editatu_hizlaria_grafismoa_deskribapena_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_grafismoa_deskribapena_" + data["hizkuntzak"][i].h_id + "' value='' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_grafismoa_esteka_" + data["hizkuntzak"][i].h_id + "'>Grafismoa esteka:</label>" +
								"<input class='input-xlarge editatu_hizlaria_grafismoa_esteka' type='text' id='editatu_hizlaria_grafismoa_esteka_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_grafismoa_esteka_" + data["hizkuntzak"][i].h_id + "' value='' />" +
							"</div>" +
						"</fieldset>"
					);
				}
				
				// Propietate orokorrak (hizkuntzei lotuak ez daudenak) berrezarri.
				$("#editatu_hizlaria_bilagarria").prop("checked", false);
				$("#editatu_hizlaria_kolorea").val("FFFFFF");
				$("#editatu_hizlaria_kolorea").css("background-color", "#FFFFFF");
			} else {
				alert("Errore bat gertatu da datu-basetik hizlariaren datuak eskuratzean:\n" + data["mezua"]);
			}
			
		}, "json");
	});
	
	$(document).on("click", ".editatu_hizlaria_botoia", function(event) {
		// editatu_hizlaria_botoia edo bere barruko <i>-a izan daitezke klikatutakoak. Biei gehitu diet data-id-hizlaria atributua.
		var id_hizlaria = $(event.target).attr("data-id-hizlaria");
		
		// id-a gorde gero datuak gorde behar badira ere.
		$("#editatu_hizlaria_id").val(id_hizlaria);
		
		// Leiho modalaren izenburuan Editatu hizlaria jarri.
		$("#editatu_hizlaria_izenburua_etiketa").text("Editatu hizlaria");
		
		// Irudiaren inputa garbitu
		$("#editatu_hizlaria_grafismoa_irudia").val("");
		
		// Aurretik egon daitezkeen Ikusi eta Ezabatu estekak kendu (eta tarteko | ere bai)
		$("#editatu_hizlaria_grafismoa_irudia_ikusi").remove();
		$('#editatu_hizlaria_grafismoa_irudia_banatzailea').remove();
		$("#editatu_hizlaria_grafismoa_irudia_ezabatu").remove();
		
		// Testuen fieldseta garbitu
		$("#editatu_hizlaria_fieldset_edukinontzia").empty();
		
		$.post("<?php echo $url_base; ?>editatu_hizlaria", {"id_hizlaria": id_hizlaria}, function(data) {
			console && console.log(data);
			
			if (data["arrakasta"]) {
				
				for (var i = 0; i < data["hizkuntzak"].length; i++) {
					
					$("#editatu_hizlaria_fieldset_edukinontzia").append("<fieldset data-h_id='" + data["hizkuntzak"][i].h_id + "' id='editatu_hizlaria_fieldset_" + i + "'>" +
							"<legend><strong>Testuak: " + data["hizkuntzak"][i].hizkuntza + "</strong></legend>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "'>Izena:</label>" +
								"<input class='input-xlarge editatu_hizlaria_izena' type='text' id='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_izena_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].izena + "' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_izen_laburra_" + data["hizkuntzak"][i].h_id + "'>Izen laburra:</label>" +
								"<input class='input-xlarge editatu_hizlaria_izen_laburra' type='text' id='editatu_hizlaria_izen_laburra_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_izen_laburra_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].izen_laburra + "' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "'>Aurrizkia:</label>" +
								"<input class='input-xlarge editatu_hizlaria_aurrizkia' type='text' id='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_aurrizkia_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].aurrizkia + "' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_gazta_etiketa_" + data["hizkuntzak"][i].h_id + "'>Gazta etiketa:</label>" +
								"<input class='input-xlarge editatu_hizlaria_gazta_etiketa' type='text' id='editatu_hizlaria_gazta_etiketa_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_gazta_etiketa_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].gazta_etiketa + "' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_grafismoa_deskribapena_" + data["hizkuntzak"][i].h_id + "'>Grafismoa deskribapena:</label>" +
								"<input class='input-xlarge editatu_hizlaria_grafismoa_deskribapena' type='text' id='editatu_hizlaria_grafismoa_deskribapena_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_grafismoa_deskribapena_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].grafismoa_deskribapena + "' />" +
							"</div>" +
							"<div class='control-group'>" +
								"<label for='editatu_hizlaria_grafismoa_esteka_" + data["hizkuntzak"][i].h_id + "'>Grafismoa esteka:</label>" +
								"<input class='input-xlarge editatu_hizlaria_grafismoa_esteka' type='text' id='editatu_hizlaria_grafismoa_esteka_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_hizlaria_grafismoa_esteka_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].grafismoa_esteka + "' />" +
							"</div>" +
						"</fieldset>"
					);
				}
				
				if (data["bilagarria"] == 1) {
					$("#editatu_hizlaria_bilagarria").prop("checked", true);
				} else {
					$("#editatu_hizlaria_bilagarria").prop("checked", false);
				}
				
				$("#editatu_hizlaria_kolorea").val(data["kolorea"]);
				
				// Hizlariak kolorerik badu kolore hori ezarri,
				// bestela zuriz margotu.
				if (data["kolorea"]) {
					$("#editatu_hizlaria_kolorea").css("background-color", "#" + data["kolorea"]);
				} else {
					$("#editatu_hizlaria_kolorea").css("background-color", "#FFFFFF");
				}
				
				// Irudirik badago bakarrik gehitu behar dira Ikusi eta Ezabatu
				if (data["path_grafismoa_irudia"] && data["grafismoa_irudia"]) {
					$("#editatu_hizlaria_grafismoa_irudia_div").append("<a id='editatu_hizlaria_grafismoa_irudia_ikusi' href='../../" + data['path_grafismoa_irudia'] + data['grafismoa_irudia'] + "' target='_blank'>Ikusi</a>");
					$("#editatu_hizlaria_grafismoa_irudia_div").append("<span id='editatu_hizlaria_grafismoa_irudia_banatzailea'>&nbsp|&nbsp<span>");
					$("#editatu_hizlaria_grafismoa_irudia_div").append("<a id='editatu_hizlaria_grafismoa_irudia_ezabatu' href='<?php echo $url_base . 'form' .  $url_param . '&edit_id=' . $edit_id; ?>&ezab_hizlaria_grafismoa_irudia_id=" + id_hizlaria + "' onClick='javascript: return (confirm(\'Grafismoaren irudia ezabatzea aukeratu duzu. Ziur al zaude?\'));'>Ezabatu</a>");
				}
			} else {
				alert("Errore bat gertatu da datu-basetik hizlariaren datuak eskuratzean:\n" + data["mezua"]);
			}
		}, "json");
	});

	// Momentua editatzeko modala bistaratzean edukien scrolla gora eraman
	$(document).on('shown', '#editatu_momentua', function () {
	    $("#editatu_momentua .modal-body").scrollTop(0);
	});
	
	$(document).on("click", "#gehitu_momentua_botoia", function(event) {
		// 0ak id berria behar duela adierazten du
		$("#editatu_momentua_id").val(0);
		
		// Leiho modalaren izenburuan Gehitu momentua jarri.
		$("#editatu_momentua_izenburua_etiketa").text("Gehitu momentua");
		
		// Irudiaren inputa garbitu
		$("#editatu_momentua_irudia").val("");
		
		// Aurretik egon daitezkeen Ikusi eta Ezabatu estekak kendu (eta tarteko | ere bai)
		$("#editatu_momentua_irudia_ikusi").remove();
		$('#editatu_momentua_irudia_banatzailea').remove();
		$("#editatu_momentua_irudia_ezabatu").remove();
		
		// Denboren kaxak garbitu
		$("#editatu_momentua_hasiera").val("");
		$("#editatu_momentua_bukaera").val("");
		
		// Testuen fieldseta garbitu
		$("#editatu_momentua_fieldset_edukinontzia").empty();
		
		// Hizkuntza kopuruaren arabera behar adina fieldset gehitu
		$.post("<?php echo $url_base; ?>editatu_momentua", {"id_momentua": 0}, function(data) {
			console && console.log(data);
			
			if (data["arrakasta"]) {
				for (var i = 0; i < data["hizkuntzak"].length; i++) {
					$("#editatu_momentua_fieldset_edukinontzia").append("<fieldset id='editatu_momentua_fieldset_" + i + "'>" +
															   "<legend><strong>Testuak: " + data["hizkuntzak"][i].hizkuntza + "</strong></legend>" +
															   "<div class='control-group'>" +
																	"<label for='editatu_momentua_testua_" + data["hizkuntzak"][i].h_id + "'>Testua:</label>" +
																	"<input class='input-xlarge editatu_momentua_testua' type='text' id='editatu_momentua_testua_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_momentua_testua_" + data["hizkuntzak"][i].h_id + "' value='' />" +
																"</div>" +
															"</fieldset>"
					);
				}
			} else {
				alert("Errore bat gertatu da datu-basetik momentuaren datuak eskuratzean:\n" + data["mezua"]);
			}
		}, "json");
	});
		
	$(document).on("click", ".editatu_momentua_botoia", function(event) {
		// editatu_momentua_botoia edo bere barruko <i>-a izan daitezke klikatutakoak. Biei gehitu diet data-id-momentua atributua.
		var id_momentua = $(event.target).attr("data-id-momentua");
		
		// id-a gorde gero datuak gorde behar badira ere.
		$("#editatu_momentua_id").val(id_momentua);
		
		// Leiho modalaren izenburuan Editatu momentua jarri.
		$("#editatu_momentua_izenburua_etiketa").text("Editatu momentua");
		
		// Irudiaren inputa garbitu
		$("#editatu_momentua_irudia").val("");
		
		// Aurretik egon daitezkeen Ikusi eta Ezabatu estekak kendu (eta tarteko | ere bai)
		$("#editatu_momentua_irudia_ikusi").remove();
		$('#editatu_momentua_irudia_banatzailea').remove();
		$("#editatu_momentua_irudia_ezabatu").remove();
		
		// Testuen fieldseta garbitu
		$("#editatu_momentua_fieldset_edukinontzia").empty();
		
		$.post("<?php echo $url_base; ?>editatu_momentua", {"id_momentua": id_momentua}, function(data) {
			console && console.log(data);
			
			if (data["arrakasta"]) {
				// Irudirik badago bakarrik gehitu behar dira Ikusi eta Ezabatu
				if (data["path_irudia"] && data["irudia"]) {
					$("#editatu_momentua_irudia_div").append("<a id='editatu_momentua_irudia_ikusi' href='../../" + data['path_irudia'] + data['irudia'] + "' target='_blank'>Ikusi</a>");
					$("#editatu_momentua_irudia_div").append("<span id='editatu_momentua_irudia_banatzailea'>&nbsp|&nbsp<span>");
					$("#editatu_momentua_irudia_div").append("<a id='editatu_momentua_irudia_ezabatu' href='<?php echo $url_base . 'form' .  $url_param . '&edit_id=' . $edit_id; ?>&ezab_momentua_irudia_id=" + id_momentua + "' onClick='javascript: return (confirm(\'Irudia ezabatzea aukeratu duzu. Ziur al zaude?\'));'>Ezabatu</a>");
				}
				
				$("#editatu_momentua_hasiera").val(egokituDenboraHHMMSSra(data["hasiera"]));
				$("#editatu_momentua_bukaera").val(egokituDenboraHHMMSSra(data["bukaera"]));
				
				for (var i = 0; i < data["hizkuntzak"].length; i++) {
					$("#editatu_momentua_fieldset_edukinontzia").append("<fieldset id='editatu_momentua_fieldset_" + i + "'>" +
															   "<legend><strong>Testuak: " + data["hizkuntzak"][i].hizkuntza + "</strong></legend>" +
															   "<div class='control-group'>" +
																	"<label for='editatu_momentua_testua_" + data["hizkuntzak"][i].h_id + "'>Testua:</label>" +
																	"<input class='input-xlarge editatu_momentua_testua' type='text' id='editatu_momentua_testua_" + data["hizkuntzak"][i].h_id + "' data-h_id='" + data["hizkuntzak"][i].h_id + "' name='editatu_momentua_testua_" + data["hizkuntzak"][i].h_id + "' value='" + data["hizkuntzak"][i].testua + "' />" +
																"</div>" +
															"</fieldset>"
					);
				}
			} else {
				alert("Errore bat gertatu da datu-basetik momentuaren datuak eskuratzean:\n" + data["mezua"]);
			}
		}, "json");
	});
	
	// Hizlarien aurkezpen grafismoa editatzeko modala bistaratzean edukien scrolla gora eraman
	$(document).on('shown', '#editatu_grafismoa', function () {
	    $("#editatu_grafismoa .modal-body").scrollTop(0);
	});
	
	// Esteken grafismoa editatzeko modala bistaratzean edukien scrolla gora eraman
	$(document).on('shown', '#editatu_infoesteka_grafismoa', function () {
	    $("#editatu_infoesteka_grafismoa .modal-body").scrollTop(0);
	});
	
	$(document).on("click", "#gehitu_grafismoa_botoia", function(event) {
		
		// 0ak id berria behar duela adierazten du
		var id_grafismoa = 0; 
		var id_eztabaida = $("#hidden_id_eztabaida").val();
		var katea = "";
		
		$("#editatu_grafismoa_id").val(id_grafismoa);
		
		// Leiho modalaren izenburuan Gehitu grafismoa jarri.
		$("#editatu_grafismoa_izenburua_etiketa").text("Gehitu grafismoa");
		
		// Propietate orokorrak (hizkuntzei lotuak ez daudenak) berrezarri.
		$("#editatu_grafismoa_hasiera").val("");
		$("#editatu_grafismoa_bukaera").val("");
		
		// Hizlarien zerrenda eskuratu zerbitzaritik eta select-ari gehitu.
		$.post("<?php echo $url_base; ?>itzuli_hizlariak", {"id_eztabaida": id_eztabaida, "h_id": <?php echo $hizkuntza['id']; ?>}, function(data) {
			console && console.log(data);
			
			if (data["arrakasta"]) {
				
				// Aurretik zeuden hizlariak kendu.
				$("#editatu_grafismoa_hizlariak").empty();
				
				// Zerbitzaritik eskuratutako hizlarien zerrenda erabiliz select-era gehitu beharreko katea prestatu.
				for (var i = 0; i < data["hizlariak"].length; i++) {
					katea = katea + "<option value='" + data["hizlariak"][i].id + "'>" + data["hizlariak"][i].izena + "</option>";
				}
				
				// Hizlariak gehitu select-ari.
				$("#editatu_grafismoa_hizlariak").append(katea);
				
			} else {
				alert("Errore bat gertatu da datu-basetik hizlarien datuak eskuratzean:\n" + data["mezua"]);
			}
		}, "json");
	});
	
	$(document).on("click", "#gehitu_infoesteka_grafismoa_botoia", function(event) {
		
		// 0ak id berria behar duela adierazten du
		var id_infoesteka_grafismoa = 0; 
		var id_eztabaida = $("#hidden_id_eztabaida").val();
		var katea = "";
		
		$("#editatu_infoesteka_grafismoa_id").val(id_infoesteka_grafismoa);
		
		// Leiho modalaren izenburuan Gehitu estekaren grafismoa jarri.
		$("#editatu_infoesteka_grafismoa_izenburua_etiketa").text("Gehitu estekaren grafismoa");
		
		// Propietate orokorrak (hizkuntzei lotuak ez daudenak) berrezarri.
		$("#editatu_infoesteka_grafismoa_hasiera").val("");
		$("#editatu_infoesteka_grafismoa_bukaera").val("");
		$("#editatu_infoesteka_grafismoa_izenburua").val("");
		$("#editatu_infoesteka_grafismoa_azalpena").val("");
		$("#editatu_infoesteka_grafismoa_esteka").val("");
		
		// Infoesteka moten zerrenda eskuratu zerbitzaritik eta select-ari gehitu.
		$.post("<?php echo $url_base; ?>itzuli_infoesteka_motak", {"h_id": <?php echo $hizkuntza['id']; ?>}, function(data) {
			console && console.log(data);
			
			if (data["arrakasta"]) {
				
				// Aurretik zeuden motak kendu.
				$("#editatu_infoesteka_grafismoa_mota").empty();
				
				// Zerbitzaritik eskuratutako moten zerrenda erabiliz select-era gehitu beharreko katea prestatu.
				for (var i = 0; i < data["motak"].length; i++) {
					katea = katea + "<option value='" + data["motak"][i].id + "'>" + data["motak"][i].izena + "</option>";
				}
				
				// Motak gehitu select-ari.
				$("#editatu_infoesteka_grafismoa_mota").append(katea);
				
			} else {
				alert("Errore bat gertatu da datu-basetik esteken grafismo moten datuak eskuratzean:\n" + data["mezua"]);
			}
		}, "json");
	});
	
	$(document).on("click", ".editatu_grafismoa_botoia", function(event) {
		
		var id_eztabaida = $("#hidden_id_eztabaida").val();
		
		// editatu_grafismoa_botoia edo bere barruko <i>-a izan daitezke klikatutakoak. Biei gehitu diet data-id-grafismoa atributua.
		var id_grafismoa = $(event.target).attr("data-id-grafismoa");
		
		// Grafismoak orain arte zuen hizlariaren id-a eskuratu.
		var id_hizlaria = $("#grafismoa_hizlaria_" + id_grafismoa).attr("data-id-hizlaria");
		
		var katea = "";
		
		// id-a gorde gero datuak gorde behar badira ere.
		$("#editatu_grafismoa_id").val(id_grafismoa);
		
		// Leiho modalaren izenburuan Editatu hizlaria jarri.
		$("#editatu_grafismoa_izenburua_etiketa").text("Editatu grafismoa");
		
		// Balioak eskuratu errenkadatik
		$("#editatu_grafismoa_hasiera").val($("#grafismoa_hasiera_" + id_grafismoa).text());
		$("#editatu_grafismoa_bukaera").val($("#grafismoa_bukaera_" + id_grafismoa).text());
		
		// Hizlarien zerrenda eskuratu zerbitzaritik eta select-ari gehitu.
		$.post("<?php echo $url_base; ?>itzuli_hizlariak", {"id_eztabaida": id_eztabaida, "h_id": <?php echo $hizkuntza['id']; ?>}, function(data) {
			console && console.log(data);
			
			if (data["arrakasta"]) {
				
				// Aurretik zeuden hizlariak kendu.
				$("#editatu_grafismoa_hizlariak").empty();
				
				// Zerbitzaritik eskuratutako hizlarien zerrenda erabiliz select-era gehitu beharreko katea prestatu.
				for (var i = 0; i < data["hizlariak"].length; i++) {
					if (id_hizlaria == data["hizlariak"][i].id) {
						katea = katea + "<option value='" + data["hizlariak"][i].id + "' selected>" + data["hizlariak"][i].izena + "</option>";
					} else {
						katea = katea + "<option value='" + data["hizlariak"][i].id + "'>" + data["hizlariak"][i].izena + "</option>";
					}
				}
				
				// Hizlariak gehitu select-ari.
				$("#editatu_grafismoa_hizlariak").append(katea);
				
			} else {
				alert("Errore bat gertatu da datu-basetik momentuaren datuak eskuratzean:\n" + data["mezua"]);
			}
		}, "json");
	});
	
	$(document).on("click", ".editatu_infoesteka_grafismoa_botoia", function(event) {
		
		var id_eztabaida = $("#hidden_id_eztabaida").val();
		
		// editatu_infoesteka_grafismoa_botoia edo bere barruko <i>-a izan daitezke klikatutakoak. Biei gehitu diet data-id-infoesteka-grafismoa atributua.
		var id_infoesteka_grafismoa = $(event.target).attr("data-id-infoesteka-grafismoa");
		
		// Infoesteka grafismoak orain arte zuen motaren id-a eskuratu.
		var id_mota = $("#grafismoa_infoesteka_mota_" + id_infoesteka_grafismoa).attr("data-id-mota");
		
		var katea = "";
		
		// id-a gorde gero datuak gorde behar badira ere.
		$("#editatu_infoesteka_grafismoa_id").val(id_infoesteka_grafismoa);
		
		// Leiho modalaren izenburuan Editatu estekaren grafismoa jarri.
		$("#editatu_infoesteka_grafismoa_izenburua_etiketa").text("Editatu estekaren grafismoa");
		
		// Infoestekaren datuak eskuratu zerbitzaritik eta bistaratu.
		$.post("<?php echo $url_base; ?>itzuli_infoesteka", {"id_infoesteka": id_infoesteka_grafismoa, "h_id": <?php echo $hizkuntza['id']; ?>}, function(data) {
			
			console && console.log(data);
			
			if (data["arrakasta"]) {
				
				$("#editatu_infoesteka_grafismoa_hasiera").val(data.hasiera);
				$("#editatu_infoesteka_grafismoa_bukaera").val(data.amaiera);
				$("#editatu_infoesteka_grafismoa_izenburua").val(data.izenburua);
				$("#editatu_infoesteka_grafismoa_azalpena").val(data.azalpena);
				$("#editatu_infoesteka_grafismoa_esteka").val(data.esteka);
				
				// Aurretik zeuden motak kendu.
				$("#editatu_infoesteka_grafismoa_mota").empty();
				
				// Zerbitzaritik eskuratutako infoesteka moten zerrenda erabiliz select-era gehitu beharreko katea prestatu.
				for (var i = 0; i < data["motak"].length; i++) {
					if (data["id_infoesteka_mota"] == data["motak"][i].id) {
						katea = katea + "<option value='" + data["motak"][i].id + "' selected>" + data["motak"][i].izena + "</option>";
					} else {
						katea = katea + "<option value='" + data["motak"][i].id + "'>" + data["motak"][i].izena + "</option>";
					}
				}
				
				// Infoesteka motak gehitu select-ari.
				$("#editatu_infoesteka_grafismoa_mota").append(katea);
				
			} else {
				
				alert("Errore bat gertatu da datu-basetik estekaren grafismoaren datuak eskuratzean:\n" + data["mezua"]);
				
			}
		}, "json");
	});
	
	$(document).ready(function() {
		
		bistaratu_azken_fitxa();
		
		// Zutabeak ezkutuan egon behar duela aukeratuta badago 'Zer bistaratu eta nola' div-eko aukerak desgaitu
		if ($('input[name=zutabea_non]:checked', '#zutabea_non_div').attr('id') === "zutabea_ezkutuan") {
			desgaitu_zer_bistaratu_eta_non(true);
		}
		
		// bilaketa_bai aukera ez badago aukeratuta gazta_bai eta barrak_bai aukerak desgaitu.
		if (!$("#bilaketa_bai").prop('checked')) {
			$("#gazta_bai").attr("disabled", true);
			$("#barrak_bai").attr("disabled", true);
		}
		
		var editatu_momentua_form_aukerak = { 
			//target:        '#output1',   // target element(s) to be updated with server response 
			beforeSubmit:   balioztatu_editatu_momentua, // editatu_momentua modalaren edukiak zerbitzarira bidali aurretik balioztatu egin behar dira.
			success:       arrakasta_editatu_momentua,   // post-submit callback 
			dataType: 	   'json'						 // 'xml', 'script', or 'json' (expected server response type)
			
			// other available options: 
			//url:       url         // override for form's 'action' attribute 
			//type:      type        // 'get' or 'post', override for form's 'method' attribute 
			//clearForm: true        // clear all form fields after successful submit 
			//resetForm: true        // reset the form after successful submit 
			
			// $.ajax options can be used here too, for example: 
			//timeout:   3000 
		}; 
		
		$('#editatu_momentua_form').submit(function() { 
			// inside event callbacks 'this' is the DOM element so we first 
			// wrap it in a jQuery object and then invoke ajaxSubmit 
			$(this).ajaxSubmit(editatu_momentua_form_aukerak); 
			
			// !!! Important !!! 
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		});
		
		function balioztatu_editatu_momentua(formData, jqForm, options) { 
			return balioztatu_denbora($("#editatu_momentua_hasiera"),
				   "Momentuaren hasierak hh:mm:ss formatuan egon behar du.",
				   $("#editatu_momentua_bukaera"),
				   "Momentuaren amaierak hh:mm:ss formatuan egon behar du."
			);
		}
		
		// 
		function balioztatu_denbora($hasiera, hasieraGaizkiMezua, $amaiera, amaieraGaizkiMezua) {
			
			// Hasiera hh:mm:ss formatuan dagoela egiaztatuko dugu
			if(/(?:[0-1]?[0-9]|[2][1-4]):[0-5]?[0-9]:[0-5]?[0-9]\s?/.test($hasiera.val()) === false) {
				
				// Erabiltzaileari hasierako denboraren formatua ez dela egokia jakinarazi.
				alert(hasieraGaizkiMezua);
				
				// Fokua hasierako testu-koadroan jarri.
				$hasiera.focus();
				
				return false;
			}
			
			// Amaiera hh:mm:ss formatuan dagoela egiaztatuko dugu
			if(/(?:[0-1]?[0-9]|[2][1-4]):[0-5]?[0-9]:[0-5]?[0-9]\s?/.test($amaiera.val()) === false) {
				
				// Erabiltzaileari amaierako denboraren formatua ez dela egokia jakinarazi.
				alert(amaieraGaizkiMezua);
				
				// Fokua amaierako testu-koadroan jarri.
				$amaiera.focus();
				
				return false;
			}
			
			return true;
		}
		
		function arrakasta_editatu_momentua(responseJSON, statusText, xhr, $form)  {
			var momentu_kop = $("#momentuak_taula tr").length;
			var katea = ""; // momentuak_taula-ri gehitu diogun katea.
			
			if (responseJSON["arrakasta"]) {
				if (responseJSON["id_momentua"]) {
					// Existitzen den momentu bat editatzen ari gara eta
					// bere izena eguneratu behar dugu zerrendan.
					// Horretarako interfazearen hizkuntzako testua erabiliko dugu.
					$("#momentua_testua_" + responseJSON["id_momentua"]).html($("#editatu_momentua_testua_<?php echo $hizkuntza['id']; ?>").val());
				} else {
					// Aurretik dauden momentu guztiei ordena posible berri bat gehitu behar zaie
					$("#momentuak_taula tr td select").each(function() {
						$(this).append("<option>" + momentu_kop + "</option>");
					});
					
					katea = "<tr>" +
								"<td>" +
										"<select class='input-mini' name='orden_" + responseJSON["id_momentu_berria"] + "' onchange='javascript:document.location=\"<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_momentua=" + responseJSON["id_momentu_berria"] + "&bal=\" + this.options[this.selectedIndex].value;'>";
					
					// Ordena posibleak: 0 eta lehendik dauden hizlari kopurua + 1 arteko guztiak.
					for (var i = 0; i <= momentu_kop; i++) {
							katea = katea + "<option value='" + i + "'>" + i + "</option>";
					}
					
					katea = katea + "</select>" +
								"</td>" +
								"<td id='momentua_izena_" + responseJSON["id_momentu_berria"] + "' class='td_klik'>" + $("#editatu_momentua_testua_<?php echo $hizkuntza['id']; ?>").val() + "</td>" +
								"<td class='td_aukerak'>" +
								   "<a href='#editatu_momentua' data-id-momentua='" + responseJSON["id_momentu_berria"] + "' role='button' class='btn editatu_momentua_botoia' data-toggle='modal'><i class='icon-pencil' data-id-momentua='" + responseJSON["id_momentu_berria"] + "'></i></a>&nbsp;" +
								   "<a class='btn' data-toggle='tooltip' title='ezabatu' href='<?php echo $url_base . 'form' .  $url_param . '&edit_id=' . $edit_id; ?>&ezab_momentua_id=" + responseJSON["id_momentu_berria"] + "' onclick='javascript: return(confirm(\"Seguru momentua ezabatu nahi duzula?\"));'><i class='icon-trash'></i></a>" +
								"</td>" +
						 "</tr>";
	
					
					
					// Momentu berri bat sortu dugu eta
					// zerrendara gehitu behar dugu.
					$("#momentuak_taula").append(katea);
				}
				
				$("#editatu_momentua").modal('hide');
			} else {
				alert("Errore bat gertatu da datu-basean momentuaren datuak gordetzean:\n" + data["mezua"]);
			}
		}
		
		var editatu_hizlaria_form_aukerak = { 
			//target:        '#output1',   // target element(s) to be updated with server response 
			//beforeSubmit:   function() {return true;}, // editatu_hizlaria modalaren edukiak zerbitzarira bidali aurretik balioztatu egin behar dira.
			success:       arrakasta_editatu_hizlaria,   // post-submit callback 
			dataType: 	   'json',						 // 'xml', 'script', or 'json' (expected server response type)
			
			// other available options: 
			//url:       url         // override for form's 'action' attribute 
			//type:      type        // 'get' or 'post', override for form's 'method' attribute 
			//clearForm: true        // clear all form fields after successful submit 
			//resetForm: true        // reset the form after successful submit 
			
			// $.ajax options can be used here too, for example: 
			//timeout:   3000 
		};
		
		$('#editatu_hizlaria_form').submit(function() {
			// inside event callbacks 'this' is the DOM element so we first 
			// wrap it in a jQuery object and then invoke ajaxSubmit 
			$(this).ajaxSubmit(editatu_hizlaria_form_aukerak); 
			
			// !!! Important !!! 
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		});
		
		function arrakasta_editatu_hizlaria(responseJSON, statusText, xhr, $form)  {
			
			// Ezkutuko elementu batean gorde dugun id berreskuratu
			var id_hizlaria = $("#editatu_hizlaria_id").val();
			var hizlari_kop = $("#hizlariak_taula tr").length;
			
			// hizlariak_taula-ri gehitu diogun katea.
			var katea = "";
			
			console.log(responseJSON);
			
			if (responseJSON["arrakasta"]) {
				if (id_hizlaria !== "0") {
					// Existitzen den hizlari bat editatzen ari gara eta
					// bere izena eguneratu behar dugu zerrendan.
					$("#hizlaria_izena_" + id_hizlaria).html($("#editatu_hizlaria_izena_<?php echo $hizkuntza["id"]; ?>").val());
				} else {
					// Aurretik dauden hizlari guztiei ordena posible berri bat gehitu behar zaie
					$("#hizlariak_taula tr td select").each(function() {
						$(this).append("<option>" + hizlari_kop + "</option>");
					});
					
					katea = "<tr>" +
								"<td>" +
									"<select class='input-mini' name='orden_" + responseJSON["id_hizlari_berria"] + "' onchange='javascript:document.location=\"<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_hizlaria=" + responseJSON["id_hizlari_berria"] + "&bal=\" + this.options[this.selectedIndex].value;'>";
					
					// Ordena posibleak: 0 eta lehendik dauden hizlari kopurua + 1 arteko guztiak.
					for (var i = 0; i <= hizlari_kop; i++) {
						katea = katea + "<option value='" + i + "'>" + i + "</option>";
					}
					
					katea = katea + "</select>" +
								"</td>" +
								"<td id='hizlaria_izena_" + responseJSON["id_hizlari_berria"] + "' class='td_klik'>" + $("#editatu_hizlaria_izena_<?php echo $hizkuntza["id"]; ?>").val() + "</td>" +
								"<td class='td_aukerak'>" +
								   "<a href='#editatu_hizlaria' data-id-hizlaria='" + responseJSON["id_hizlari_berria"] + "' role='button' class='btn editatu_hizlaria_botoia' data-toggle='modal'><i class='icon-pencil' data-id-hizlaria='" + responseJSON["id_hizlari_berria"] + "'></i></a>&nbsp;" +
								   "<a class='btn' data-toggle='tooltip' title='ezabatu' href='<?php echo $url_base . 'form' .  $url_param . '&edit_id=' . $edit_id; ?>&ezab_hizlaria_id=" + responseJSON["id_hizlari_berria"] + "' onclick='javascript: return(confirm(\"Seguru hizlaria ezabatu nahi duzula?\"));'><i class='icon-trash'></i></a>" +
								"</td>" +
							 "</tr>";
					
					// Hizlari berri bat sortu dugu eta
					// zerrendara gehitu behar dugu.
					$("#hizlariak_taula").append(katea);
				}
				
				$("#editatu_hizlaria").modal('hide');
			} else {
				alert("Errore bat gertatu da datu-basean hizlariaren datuak gordetzean:\n" + data["mezua"]);
			}
		}
		
		/*$(document).on("click", "#editatu_hizlaria_gorde_botoia", function(event) {
			// Ezkutuko elementu batean gorde dugun id berreskuratu
			var id_hizlaria = $("#editatu_hizlaria_id").val();
			var id_eztabaida = $("#hidden_id_eztabaida").val();
			var katea = ""; // hizlariak_taula-ri gehitu diogun katea.
			var bilagarria = 0;
			var hizlari_kop = $("#hizlariak_taula tr").length;
			
			if ($("#editatu_hizlaria_bilagarria").is(':checked')) {
				bilagarria = 1;
			}
			
			var balioak = {
				"gorde": "gorde",
				"id_eztabaida": id_eztabaida,
				"id_hizlaria": id_hizlaria,
				"bilagarria": bilagarria,
				"kolorea": $("#editatu_hizlaria_kolorea").val()
			}
			
			$("#editatu_hizlaria_fieldset_edukinontzia fieldset").each(function() {
				
				var h_id = $(this).attr("data-h_id");
				
				balioak["editatu_hizlaria_izena_" + h_id] = $("#editatu_hizlaria_izena_" + h_id).val();
				balioak["editatu_hizlaria_izen_laburra_" + h_id] = $("#editatu_hizlaria_izen_laburra_" + h_id).val();
				balioak["editatu_hizlaria_aurrizkia_" + h_id] = $("#editatu_hizlaria_aurrizkia_" + h_id).val();
				balioak["editatu_hizlaria_gazta_etiketa_" + h_id] = $("#editatu_hizlaria_gazta_etiketa_" + h_id).val();
				balioak["editatu_hizlaria_grafismoa_deskribapena_" + h_id] = $("#editatu_hizlaria_grafismoa_deskribapena_" + h_id).val();
				balioak["editatu_hizlaria_grafismoa_esteka_" + h_id] = $("#editatu_hizlaria_grafismoa_esteka_" + h_id).val();
			});
			
			$.post("<?php echo $url_base; ?>editatu_hizlaria", 
				balioak,
				function(data) {
					console && console.log(data);
					
					if (data["arrakasta"]) {
						if (id_hizlaria !== "0") {
							// Existitzen den hizlari bat editatzen ari gara eta
							// bere izena eguneratu behar dugu zerrendan.
							$("#hizlaria_izena_" + id_hizlaria).html($("#editatu_hizlaria_izena_<?php echo $hizkuntza["id"]; ?>").val());
						} else {
							// Aurretik dauden hizlari guztiei ordena posible berri bat gehitu behar zaie
							$("#hizlariak_taula tr td select").each(function() {
								$(this).append("<option>" + hizlari_kop + "</option>");
							});
							
							katea = "<tr>" +
										"<td>" +
											"<select class='input-mini' name='orden_" + data["id_hizlari_berria"] + "' onchange='javascript:document.location=\"<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_hizlaria=" + data["id_hizlari_berria"] + "&bal=\" + this.options[this.selectedIndex].value;'>";
							
							// Ordena posibleak: 0 eta lehendik dauden hizlari kopurua + 1 arteko guztiak.
							for (var i = 0; i <= hizlari_kop; i++) {
								katea = katea + "<option value='" + i + "'>" + i + "</option>";
							}
							
							katea = katea + "</select>" +
										"</td>" +
										"<td id='hizlaria_izena_" + data["id_hizlari_berria"] + "' class='td_klik'>" + $("#editatu_hizlaria_izena_<?php echo $hizkuntza["id"]; ?>").val() + "</td>" +
										"<td class='td_aukerak'>" +
										   "<a href='#editatu_hizlaria' data-id-hizlaria='" + data["id_hizlari_berria"] + "' role='button' class='btn editatu_hizlaria_botoia' data-toggle='modal'><i class='icon-pencil' data-id-hizlaria='" + data["id_hizlari_berria"] + "'></i></a>&nbsp;" +
										   "<a class='btn' data-toggle='tooltip' title='ezabatu' href='<?php echo $url_base . 'form' .  $url_param . '&edit_id=' . $edit_id; ?>&ezab_hizlaria_id=" + data["id_hizlari_berria"] + "' onclick='javascript: return(confirm(\"Seguru hizlaria ezabatu nahi duzula?\"));'><i class='icon-trash'></i></a>" +
										"</td>" +
									 "</tr>";
							
							// Hizlari berri bat sortu dugu eta
							// zerrendara gehitu behar dugu.
							$("#hizlariak_taula").append(katea);
						}
					
						$("#editatu_hizlaria").modal('hide');
					} else {
						alert("Errore bat gertatu da datu-basean hizlariaren datuak gordetzean:\n" + data["mezua"]);
					}
				}, "json"
			);
		});*/
		
		$("#bilaketa_bai").change(function() {
			// Bilaketa ez badugu bistaratu behar, gaztaren eta barren checkbox-ak desgaitu behar ditugu.
			if (!$(this).prop('checked')) {
				$("#gazta_bai").attr("disabled", true);
				$("#barrak_bai").attr("disabled", true);
			} else {
				$("#gazta_bai").removeAttr("disabled");
				$("#barrak_bai").removeAttr("disabled");
			}
		});
		
		$('input[name=zutabea_non]', '#zutabea_non_div').change(function() {
			// Zutabea ezkutuan jartzea hautatzean 'Zer bistaratu eta nola' ataleko aukerak desgaitu,
			// bestela gaitu.
			if (this.id === "zutabea_ezkutuan") {
				desgaitu_zer_bistaratu_eta_non(true);
			} else {
				desgaitu_zer_bistaratu_eta_non(false);
			}
		});
		
		// zer_bistaratu_eta_non ataleko aukerak desgaitzen ditu true pasatzean, false pasatzean berriz gaitzen ditu.
		function desgaitu_zer_bistaratu_eta_non(balioa) {
			$("#momenturik_onenak_bai").attr("disabled", balioa);
			$("#bilaketa_bai").attr("disabled", balioa);
			$("#gazta_bai").attr("disabled", balioa);
			$("#partekatu_bai").attr("disabled", balioa);
			$("#txertatu_bai").attr("disabled", balioa);
			$("#lizentzia_bai").attr("disabled", balioa);
			$("#barrak_bai").attr("disabled", balioa);
		}
		
		$("#editatu-hipertranskribapena-botoia").click(function() {
			
			// Azpitituluen fitxategirik ez badago Editatu hipertranskribapena botoia sakatzean abisu bat bistaratu behar litzateke.
			//if () {
			//} else {
				window.location = "<?php echo $url_base; ?>editatu-hipertranskribapena&edit_id=<?php echo $edit_id; ?>&h_id=<?php echo $h_id; ?>";
			//}
		});
		
		// Fitxa bat erakustean biltegiratze lokalean gorde (eztabaida bakoitzaren azken fitxa aldagai desberdin batean gordetzen da).
		$('.nav-tabs a').on('shown', function () {
			localStorage.setItem('lastTab_<?php echo $edit_id; ?>', $(this).attr('href'));
		});
		
		function bistaratu_azken_fitxa() {
			
			// Eztabaida honetan erabilitako azken fitxa berreskuratu biltegiratze lokaletik.
			var lastTab = localStorage.getItem('lastTab_<?php echo $edit_id; ?>');
			
			if (lastTab) {
				// Azken fitxa badago bistaratu.
				$('a[href=' + lastTab + ']').tab('show');
				//$('.nav-tabs a[href=#' + lastTab + ']').tab('show') ;
			} else {
				// Ez badago azken fitxarik bistaratu lehen fitxa.
				$('a[data-toggle="tab"]:first').tab('show');
			}
		}
		
		$(document).on("click", "#editatu_grafismoa_gorde_botoia", function(event) {
		
			event.preventDefault();
			
			// Ezkutuko elementu batean gorde dugun id berreskuratu
			var id_grafismoa = $("#editatu_grafismoa_id").val();
			var id_eztabaida = $("#hidden_id_eztabaida").val();
			var katea = ""; // hizlariak_taula-ri gehitu diogun katea.
			var grafismo_kop = $("#grafismoak_taula tr").length;
			
			var balioak = {
				"gorde": "gorde",
				"editatu_grafismoa_id_eztabaida": id_eztabaida,
				"editatu_grafismoa_id": id_grafismoa,
				"editatu_grafismoa_hasiera": $("#editatu_grafismoa_hasiera").val(),
				"editatu_grafismoa_bukaera": $("#editatu_grafismoa_bukaera").val(),
				"editatu_grafismoa_id_hizlaria": $("#editatu_grafismoa_hizlariak").val()
			}
			
			var hizlaria_izena = $("#editatu_grafismoa_hizlariak option:selected").text();
			
			if (!balioztatu_denbora($("#editatu_grafismoa_hasiera"),
					   "Grafismoaren hasierak hh:mm:ss formatuan egon behar du.",
					   $("#editatu_grafismoa_bukaera"),
					   "Grafismoaren amaierak hh:mm:ss formatuan egon behar du."
			)) {
				return false;
			}
			
			$.post("<?php echo $url_base; ?>editatu_grafismoa", 
				balioak,
				function(data) {
					
					console && console.log(data);
					
					if (data["arrakasta"]) {
						
						if (id_grafismoa !== "0") {
							
							// Existitzen den grafismo bat editatzen ari gara eta
							// bere datuak eguneratu behar ditugu zerrendan.
							$("#grafismoa_hasiera_" + id_grafismoa).html(balioak.editatu_grafismoa_hasiera);
							$("#grafismoa_bukaera_" + id_grafismoa).html(balioak.editatu_grafismoa_bukaera);
							$("#grafismoa_hizlaria_" + id_grafismoa).attr("data-id-hizlaria", balioak.editatu_grafismoa_id_hizlaria);
							$("#grafismoa_hizlaria_" + id_grafismoa).text(hizlaria_izena);
							
						} else {
							
							// Aurretik dauden grafismo guztiei ordena posible berri bat gehitu behar zaie
							$("#grafismoak_taula tr td select").each(function() {
								$(this).append("<option>" + grafismo_kop + "</option>");
							});
							
							katea = "<tr>" +
										"<td>" +
											"<select class='input-mini' name='orden_" + data["id_grafismo_berria"] +
											"' onchange='javascript:document.location=\"<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_grafismoa=" + data["id_grafismo_berria"] +
											"&bal=\" + this.options[this.selectedIndex].value;'>";
							
							// Ordena posibleak: 0 eta lehendik dauden grafismo kopurua + 1 arteko guztiak.
							for (var i = 0; i <= grafismo_kop; i++) {
								katea = katea + "<option value='" + i + "'>" + i + "</option>";
							}
							
							katea = katea + "</select>" +
										"</td>" +
										"<td id='grafismoa_hasiera_" + data["id_grafismo_berria"] + "' class='td_klik'>" + balioak.editatu_grafismoa_hasiera + "</td>" +
										"<td id='grafismoa_bukaera_" + data["id_grafismo_berria"] + "' class='td_klik'>" + balioak.editatu_grafismoa_bukaera + "</td>" +
										"<td id='grafismoa_hizlaria_" + data['id_grafismo_berria'] + "' class='td_klik' data-id-hizlaria='" + balioak.editatu_grafismoa_id_hizlaria + "'>" + hizlaria_izena + "</td>" +
										"<td class='td_aukerak'>" + 
											"<a href='#editatu_grafismoa' data-id-grafismoa='" + data['id_grafismo_berria'] + "' role='button' class='btn editatu_grafismoa_botoia' data-toggle='modal'><i class='icon-pencil' data-id-grafismoa='" + data['id_grafismo_berria'] + "'></i></a>&nbsp;" +
											"<a class='btn' data-toggle='tooltip' title='ezabatu' href='<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_grafismoa_id=" + data['id_grafismo_berria'] + "' onclick='javascript: return (confirm (\"Seguru grafismoa ezabatu nahi duzula?\"));'><i class='icon-trash'></i></a>" +
										"</td>" +
									 "</tr>";
							
							// Grafismo berri bat sortu dugu eta
							// zerrendara gehitu behar dugu.
							$("#grafismoak_taula").append(katea);
						}
					
						$("#editatu_grafismoa").modal('hide');
					} else {
						alert("Errore bat gertatu da datu-basean grafismoaren datuak gordetzean:\n" + data["mezua"]);
					}
				}, "json"
			);
		});
		
		$(document).on("click", "#editatu_infoesteka_grafismoa_gorde_botoia", function(event) {
			
			event.preventDefault();
			
			// Ezkutuko elementu batean gorde dugun id berreskuratu
			var id_infoesteka_grafismoa = $("#editatu_infoesteka_grafismoa_id").val();
			var id_eztabaida = $("#hidden_id_eztabaida").val();
			var katea = ""; // hizlariak_taula-ri gehitu diogun katea.
			var infoesteka_grafismo_kop = $("#infoestekak_grafismoak_taula tr").length;
			
			var balioak = {
				"gorde": "gorde",
				"editatu_infoesteka_grafismoa_id_eztabaida": id_eztabaida,
				"editatu_infoesteka_grafismoa_id": id_infoesteka_grafismoa,
				"editatu_infoesteka_grafismoa_hasiera": $("#editatu_infoesteka_grafismoa_hasiera").val(),
				"editatu_infoesteka_grafismoa_bukaera": $("#editatu_infoesteka_grafismoa_bukaera").val(),
				"editatu_infoesteka_grafismoa_izenburua": $("#editatu_infoesteka_grafismoa_izenburua").val(),
				"editatu_infoesteka_grafismoa_azalpena": $("#editatu_infoesteka_grafismoa_azalpena").val(),
				"editatu_infoesteka_grafismoa_esteka": $("#editatu_infoesteka_grafismoa_esteka").val(),
				"editatu_infoesteka_grafismoa_mota": $("#editatu_infoesteka_grafismoa_mota").val()
			}
			
			if (!balioztatu_denbora($("#editatu_infoesteka_grafismoa_hasiera"),
					   "Estekaren grafismoaren hasierak hh:mm:ss formatuan egon behar du.",
					   $("#editatu_infoesteka_grafismoa_bukaera"),
					   "Estekaren grafismoaren amaierak hh:mm:ss formatuan egon behar du."
			)) {
				return false;
			}
			
			$.post("<?php echo $url_base; ?>editatu_infoesteka_grafismoa", 
				balioak,
				function(data) {
					
					console && console.log(data);
					
					if (data["arrakasta"]) {
						
						if (id_infoesteka_grafismoa !== "0") {
							
							// Existitzen den infoestekaren grafismo bat editatzen ari gara eta
							// bere datuak eguneratu behar ditugu zerrendan.
							$("#infoesteka_grafismoa_hasiera_" + id_infoesteka_grafismoa).html(balioak.editatu_infoesteka_grafismoa_hasiera);
							$("#infoesteka_grafismoa_bukaera_" + id_infoesteka_grafismoa).html(balioak.editatu_infoesteka_grafismoa_bukaera);
							$("#infoesteka_grafismoa_esteka_" + id_infoesteka_grafismoa).html(balioak.editatu_infoesteka_grafismoa_esteka);
							
						} else {
							
							katea = "<tr>" +
										"<td id='infoesteka_grafismoa_hasiera_" + data["id_infoesteka_grafismo_berria"] + "' class='td_klik'>" + balioak.editatu_infoesteka_grafismoa_hasiera + "</td>" +
										"<td id='infoesteka_grafismoa_bukaera_" + data["id_infoesteka_grafismo_berria"] + "' class='td_klik'>" + balioak.editatu_infoesteka_grafismoa_bukaera + "</td>" +
										"<td class='td_klik' id='infoesteka_grafismoa_esteka_" + data["id_infoesteka_grafismo_berria"] + "' style='cursor: pointer;'>" + balioak.editatu_infoesteka_grafismoa_esteka + "</td>" +
										"<td class='td_aukerak'>" + 
											"<a href='#editatu_infoesteka_grafismoa' data-id-infoesteka-grafismoa='" + data['id_infoesteka_grafismo_berria'] + "' role='button' class='btn editatu_infoesteka_grafismoa_botoia' data-toggle='modal'><i class='icon-pencil' data-id-infoesteka-grafismoa='" + data['id_infoesteka_grafismo_berria'] + "'></i></a>&nbsp;" +
											"<a class='btn' data-toggle='tooltip' title='ezabatu' href='<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_infoesteka_grafismoa_id=" + data['id_infoesteka_grafismo_berria'] + "' onclick='javascript: return (confirm (\"Ziur zaude estekaren grafismoa ezabatu nahi duzula?\"));'><i class='icon-trash'></i></a>" +
										"</td>" +
									 "</tr>";
							
							// Zerrendan dagoeneko elementuak badaude.
							if ($("#infoestekak_grafismoak_taula tbody tr").length > 0) {
								
								// Elementua non txertatu jakin behar dugu. Horretarako zerrendako elementuak banan bana pasako ditugu.
								$("#infoestekak_grafismoak_taula tbody tr").each(function(index, element) {
									
									// Infoesteka berriaren hasiera baino beranduago hasten bada, bere aurretik txertatuko dugu elementu berria.
									if ($(this).children(":first").text() > balioak.editatu_infoesteka_grafismoa_hasiera) {
										
										$(this).before(katea);
										
										// each-etik irtengo gara.
										return false;
									}
									
									// Azkenengo elementua baino beranduago hasten bada azken elementuaren ondoren txertatu.
									if (index == $("#infoestekak_grafismoak_taula tbody tr").length - 1) {
										$(this).after(katea);
									}
								});
							} else {
								
								// Ez dago errenkadarik, append besterik gabe.
								$("#infoestekak_grafismoak_taula").append(katea);
							}
						}
					
						$("#editatu_infoesteka_grafismoa").modal('hide');
					} else {
						alert("Errore bat gertatu da datu-basean estekaren grafismoaren datuak gordetzean:\n" + data["mezua"]);
					}
				}, "json"
			);
		});
	});
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand"><a href="<?php echo URL_BASE; ?>eztabaidak">Eztabaidak</a> > <?php echo $eztabaida->hizkuntzak[$h_id]->izenburua; ?></div>
		
		<div class="pull-right">
			<a class="btn" href="<?php echo $url_base . $url_param; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
		</div>
	</div>
</div>

<ul class="nav nav-tabs" id="myTab">
	<li><a href="#eztabaidak-fitxa-orokorra" data-toggle="tab">Orokorra</a></li>
	<li><a href="#eztabaidak-fitxa-azpitituluak" data-toggle="tab">Hipertranskribapena eta azpitituluak</a></li>
	<li><a href="#eztabaidak-fitxa-hizlariak" data-toggle="tab">Hizlariak</a></li>
	<li><a href="#eztabaidak-fitxa-momentuak" data-toggle="tab">Momenturik onenak</a></li>
	<li><a href="#eztabaidak-fitxa-grafismoak" data-toggle="tab">Grafismoak</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane formularioa" id="eztabaidak-fitxa-orokorra">
		<form id="f1" name="f1" method="post" action="<?php echo $url_base . "form" . $url_param; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
			<input type="hidden" name="gorde" value="BAI" />
			<input id="hidden_id_eztabaida" type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
			
			<fieldset>
				<legend><strong>Zer bistaratu eta nola</strong></legend>
				
				<fieldset>
					<div id="zer_bistaratu_eta_nola_div" class="control-group">
						<label class="checkbox">
							<input id="momenturik_onenak_bai" type="checkbox" name="momenturik_onenak_bai" value="1"<?php if ($eztabaida->momenturik_onenak_bai == 1) {echo " checked";} ?>>Momenturik onenak?
						</label>
						<label class="checkbox">
							<input id="bilaketa_bai" type="checkbox" name="bilaketa_bai" value="1"<?php if ($eztabaida->bilaketa_bai == 1) {echo " checked";} ?>>Bilaketa?
						</label>
						<label class="checkbox">
							<input id="gazta_bai" type="checkbox" name="gazta_bai" value="1"<?php if ($eztabaida->gazta_bai == 1) {echo " checked";} ?>>Gazta?
						</label>
						<label class="checkbox">
							<input id="partekatu_bai" type="checkbox" name="partekatu_bai" value="1"<?php if ($eztabaida->partekatu_bai == 1) {echo " checked";} ?>>Partekatu?
						</label>
						<label class="checkbox">
							<input id="txertatu_bai" type="checkbox" name="txertatu_bai" value="1"<?php if ($eztabaida->txertatu_bai == 1) {echo " checked";} ?>>Txertatu?
						</label>
						<label class="checkbox">
							<input id="lizentzia_bai" type="checkbox" name="lizentzia_bai" value="1"<?php if ($eztabaida->lizentzia_bai == 1) {echo " checked";} ?>>Lizentzia?
						</label>
						<label class="checkbox">
							<input id="barrak_bai" type="checkbox" name="barrak_bai" value="1"<?php if ($eztabaida->barrak_bai == 1) {echo " checked";} ?>>Barrak?
						</label>
					</div>
				</fieldset>
				
				<fieldset>
					<legend><strong>Zutabea non?</strong></legend>
					<div id="zutabea_non_div" class="control-group">
						<label class="radio">
							<input id="zutabea_ezkerrean" type="radio" name="zutabea_non" value="0"<?php if ($eztabaida->zutabea_non == 0) {echo " checked";} ?>>Ezkerrean
						</label>
						<label class="radio">
							<input id="zutabea_eskuinean" type="radio" name="zutabea_non" value="1"<?php if ($eztabaida->zutabea_non == 1) {echo " checked";} ?>>Eskuinean
						</label>
						<label class="radio">
							<input id="zutabea_ezkutuan" type="radio" name="zutabea_non" value="2"<?php if ($eztabaida->zutabea_non == 2) {echo " checked";} ?>>Ezkutuan
						</label>
					</div>
				</fieldset>
			</fieldset>
			
			<fieldset>
				<legend><strong>Irudiak</strong></legend>
				
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="posterra">Posterra:</label>
						<div class="controls">
							<input class="input-xxlarge" name="posterra" type="file" id="posterra"  />
							<?php
								if (is_file ("../" . $eztabaida->posterra)){
									echo "<a href=\"../../$eztabaida->posterra\" target=\"_blank\">Ikusi</a>";
									echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&ezabatu=POSTERRA\" onClick=\"javascript: return (confirm ('Posterra ezabatzea aukeratu duzu. Ziur al zaude?'));\">Ezabatu</a>";
								}
							?>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="lizentzia">Lizentziaren logoa:</label>
						<div class="controls">
							<input class="input-xxlarge" name="lizentzia" type="file" id="lizentzia"  />
							<?php
								if (is_file ("../" . $eztabaida->lizentzia)){
									echo "<a href=\"../../$eztabaida->lizentzia\" target=\"_blank\">Ikusi</a>";
									echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&ezabatu=LIZENTZIA\" onClick=\"javascript: return (confirm ('Lizentziaren logoa ezabatzea aukeratu duzu. Ziur al zaude?'));\">Ezabatu</a>";
								}
							?>
						</div>
					</div>
				</fieldset>
			</fieldset>
			
			<fieldset>
				<legend><strong>Bideoa</strong></legend>
				
				<div class="control-group">
					<label for="url_bideoa">Bideoaren URLa (Youtube edo Vimeo):</label>
					<input class="input-xxlarge" type="text" id="url_bideoa" name="url_bideoa" value="<?php echo testu_formatua_input($eztabaida->url_bideoa); ?>" />
				</div>
				
				<!--
				<div class="control-group">
					<label for="bideoak_kalitateak">Bideo-kalitate lehenetsia:</label>
				
					<select id="bideoak_kalitateak" name="bideoak_kalitateak">
					
					<?php
					
					for ($i = 0; $i < count($eztabaida->bideo_kalitateak); $i++) {
						if ($eztabaida->bideo_kalitateak[$i] != $eztabaida->kalitate_lehenetsia) {
							echo "<option value='" . $eztabaida->bideo_kalitateak[$i] . "'>" . get_dbtable_field_by_id_hizkuntza("eztabaidak_bideoak_kalitateak", "izena", $eztabaida->bideo_kalitateak[$i], $hizkuntza["id"]) . "</option>";
						} else {
							echo "<option value='" . $eztabaida->bideo_kalitateak[$i] . "' selected>" . get_dbtable_field_by_id_hizkuntza("eztabaidak_bideoak_kalitateak", "izena", $eztabaida->bideo_kalitateak[$i], $hizkuntza["id"]) . "</option>";
						}
						
					}
					
					?>
					
					</select>
					
				</div>
				
				<?php 
				foreach($eztabaida->bideo_kalitate_guztien_idak as $bk) {
					// Bideo kalitate bakoitzarentzako fieldset bat sortuko dugu.
					echo "<fieldset>" .
							"<legend><strong>" . get_dbtable_field_by_id_hizkuntza("eztabaidak_bideoak_kalitateak", "izena", $bk, $hizkuntza["id"]) . "</strong></legend>" .
								"<table id='bideoak_taula_" . $bk . "' class='table table-bordered table-hover'>" .
									"<thead>" .
										"<tr>" .
											"<th width='100'>Mota</th>" .
											"<th>Bideoa</th>" .
											"<th width='85'><a id='gehitu_bideoa_botoia_" . $bk . "' class='btn' href=''>Gehitu&nbsp;<i class='icon-plus-sign'></i></a></th>" .
										"</tr>" .
									"<thead>" .
									"<tbody>";
										// Bideo bakoitza dagokion kalitatearen fieldset-ean jarriko dugu.
										for($i = 0; $i < count($eztabaida->bideoak); $i++) {
											for ($j = 0; $j < count($eztabaida->bideoak[$i]); $j++) {
												if ($eztabaida->bideoak[$i][$j]->kalitatea == $bk) {
													echo "<tr>";
													echo "<td>" . $eztabaida->bideoak[$i][$j]->mota . "</td>";
													echo "<td>" . $eztabaida->bideoak[$i][$j]->path . $eztabaida->bideoak[$i][$j]->bideoa . "</td>";
													echo "<td class='td_aukerak'>";
													echo "<a class='btn' data-toggle='tooltip' title='ezabatu' href='" . $url_base . "form" .  $url_param . "&edit_id=" . $edit_id . "&ezab_bideoa_id=" . $eztabaida->bideoak[$i][$j]->id . "' onclick='javascript: return (confirm (\'Seguru hizlaria ezabatu nahi duzula?\'));'><i class='icon-trash'></i></a>";
													echo "</td>";
													echo "</tr>";
												}
											}
										}
									echo "</tbody>" .
								"</table>";
					echo "</fieldset>";
				};
				?>
				-->
			</fieldset>
			
			<fieldset>
				<legend><strong>Koloreak</strong></legend>
				
				<div class="control-group">
					<label for="koloreak_gazta_testua">Gaztaren testuaren kolorea:</label>
					<input class="color" id="koloreak_gazta_testua" name="koloreak_gazta_testua" value="<?php echo $eztabaida->gazta_testu_kolorea; ?>" />
				</div>
				
				<div class="control-group">
					<label for="koloreak_gazta_marra">Gaztaren marren kolorea:</label>
					<input class="color" id="koloreak_gazta_marra" name="koloreak_gazta_marra" value="<?php echo $eztabaida->gazta_marra_kolorea; ?>" />
				</div>
				
				<div class="control-group">
					<label for="koloreak_barrak_testua">Barren testuaren kolorea:</label>
					<input class="color" id="koloreak_barrak_testua" name="koloreak_barrak_testua" value="<?php echo $eztabaida->barrak_testu_kolorea; ?>" />
				</div>
			</fieldset>
			
			<?php
				foreach (hizkuntza_idak () as $h_id) {
			?>
			<fieldset>
				<legend><strong>Testuak: <?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
				
				<div class="control-group">
					<label for="izenburua_<?php echo $h_id; ?>">Izenburua:</label>
					<input class="input-xxlarge" type="text" id="izenburua_<?php echo $h_id; ?>" name="izenburua_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input($eztabaida->hizkuntzak[$h_id]->izenburua); ?>" />
				</div>
							
				<div class="control-group">
					<label for="hash_tag_<?php echo $h_id; ?>">Traolak:</label>
					<input class="input-xxlarge" type="text" id="hash_tag_<?php echo $h_id; ?>" name="hash_tag_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($eztabaida->hizkuntzak[$h_id]->hash_tag); ?>" />
				</div>
				
				<div class="control-group">
					<label for="bilaketa_kaxa_testua_<?php echo $h_id; ?>">Bilaketa kaxaren testua:</label>
					<input class="input-xxlarge" type="text" id="bilaketa_kaxa_testua_<?php echo $h_id; ?>" name="bilaketa_kaxa_testua_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($eztabaida->hizkuntzak[$h_id]->bilaketa_kaxa_testua); ?>" />
				</div>
				
				<div class="control-group">
					<label for="fb_izenburua_<?php echo $h_id; ?>">Facebookeko izenburua:</label>
					<input class="input-xxlarge" type="text" id="fb_izenburua_<?php echo $h_id; ?>" name="fb_izenburua_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($eztabaida->hizkuntzak[$h_id]->fb_izenburua); ?>" />
				</div>
				
				<div class="control-group">
					<label for="url_lizentzia_<?php echo $h_id; ?>">Lizentziaren URLa:</label>
					<input class="input-xxlarge" type="text" id="url_lizentzia_<?php echo $h_id; ?>" name="url_lizentzia_<?php echo $h_id; ?>" value="<?php echo testu_formatua_input ($eztabaida->hizkuntzak[$h_id]->url_lizentzia); ?>" />
				</div>
			</fieldset>
			<?php
				}
			?>
			
			<div class="control-group text-center">
				<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
				<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
			</div>
		</form>
	</div>
	
	<div class="tab-pane" id="eztabaidak-fitxa-hizlariak">
		<fieldset>
			<legend><strong>Hizlariak</strong></legend>
			
			<table id="hizlariak_taula" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="50">Ordena</th>
						<th>Izena</th>
						<th width="85">
							<a id="gehitu_hizlaria_botoia" class="btn" href="#editatu_hizlaria" data-toggle="modal">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$orden_max = orden_max("eztabaidak_hizlariak", "fk_elem = " . $edit_id);
						
						foreach ($eztabaida->hizlariak as $elem) {
					?>
					<tr <?php if ($klassak) { echo current($klassak); } ?>>
						<td>
							<select class="input-mini" name="orden_<?php echo $elem->id; ?>" onchange="javascript:document.location='<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_hizlaria=<?php echo $elem->id; ?>&bal=' + this.options[this.selectedIndex].value;">
								<option value="0">0</option>
							<?php for ($i = 1; $i <= ($elem->orden == 0 ? $orden_max + 1 : $orden_max); $i++){ ?>
								<option value="<?php echo $i; ?>"<?php echo $i == $elem->orden ? " selected" : ""; ?>><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</td>
						<td id="hizlaria_izena_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->izena; ?></td>
						<td class="td_aukerak">
							<a href="#editatu_hizlaria" data-id-hizlaria="<?php echo $elem->id; ?>" role="button" class="btn editatu_hizlaria_botoia" data-toggle="modal"><i class="icon-pencil" data-id-hizlaria="<?php echo $elem->id; ?>"></i></a>
							<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_hizlaria_id=<?php echo $elem->id; ?>" onclick="javascript: return (confirm ('Seguru hizlaria ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
						</td>
					</tr>
					<?php if ($klassak && !next($klassak)) { reset($klassak); } } ?>
				</tbody>
			</table>
		</fieldset>
		
		<div id="editatu_hizlaria" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editatu_hizlaria_izenburua_etiketa" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="editatu_hizlaria_izenburua_etiketa"></h3>
			</div>
			
			<form id="editatu_hizlaria_form" method="post" action="<?php echo $url_base; ?>editatu_hizlaria">
				<div class="modal-body">
					<fieldset>
						<input type="hidden" name="gorde" value="BAI" />
						<input type="hidden" id="editatu_hizlaria_id" name="editatu_hizlaria_id" value="" />
						<input type="hidden" id="editatu_hizlaria_id_eztabaida" name="editatu_hizlaria_id_eztabaida" value="" />
						<span id="editatu_hizlaria_fieldset_edukinontzia"></span>
						<div class="control-group">
							<label class="checkbox">
								<input id="editatu_hizlaria_bilagarria" type="checkbox" name="editatu_hizlaria_bilagarria" value="1">Bilagarria?
							</label>
						</div>
						<div class="control-group">
							<label for="editatu_hizlaria_kolorea">Kolorea:</label>
							<input class="color" id="editatu_hizlaria_kolorea" name="editatu_hizlaria_kolorea" />
						</div>
						<fieldset>
							<legend><strong>Irudiak</strong></legend>
							<div class="control-group">
								<label class="control-label" for="editatu_hizlaria_grafismoa_irudia">Grafismoko irudia:</label>
								<div id="editatu_hizlaria_grafismoa_irudia_div" class="controls">
									<input class="input-xxlarge" name="editatu_hizlaria_grafismoa_irudia" type="file" id="editatu_hizlaria_grafismoa_irudia" />
								</div>
							</div>
						</fieldset>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
					<button id="editatu_hizlaria_gorde_botoia" class="btn btn-primary" type="submit">Gorde</button>
				</div>
			</form>
		</div>
	</div>
	
	<div class="tab-pane" id="eztabaidak-fitxa-azpitituluak">
		<form id="editatu_azpitituluak_form" enctype="multipart/form-data" method="post" action="<?php echo $url_base; ?>gorde_azpitituluak_fitxa">
			<input type="hidden" name="gorde" value="BAI" />
			<input id="hidden_id_eztabaida" type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
			<?php
				foreach (hizkuntza_idak() as $h_id) {
			?>
			<fieldset>
				<legend><strong><?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?></strong></legend>
				<div class="control-group">
					<label class="control-label" for="lizentzia">SRT azpitituluak:</label>
					<?php if (!is_file ("../" . $eztabaida->hizkuntzak[$h_id]->azpitituluak)) {echo "<div>OHARRA: Hipertranskribapena sortu ahal izateko SRT azpititulu bat gehitu eta gorde botoia sakatu behar duzu lehenik.</div>"; } ?>
					<div class="controls">
						<input class="input-xxlarge" name="azpitituluak_<?php echo $h_id; ?>" type="file" id="azpitituluak_<?php echo $h_id; ?>" />
						<?php
							if (is_file ("../" . $eztabaida->hizkuntzak[$h_id]->azpitituluak)) {
								echo "<a href=\"../../" . $eztabaida->hizkuntzak[$h_id]->azpitituluak . "\" target=\"_blank\">Ikusi</a>";
								echo "&nbsp;|&nbsp;<a href=\"" . $url_base . "form" . $url_param . "&edit_id=" . $edit_id . "&h_id=" . $h_id . "&ezabatu=AZPITITULUA\" onClick=\"javascript: return (confirm ('Azpitituluak ezabatzea aukeratu duzu. Ziur al zaude?'));\">Ezabatu</a>";
							}
						?>
						<button id="editatu-hipertranskribapena-botoia" type="button" class="btn"<?php if (!is_file ("../" . $eztabaida->hizkuntzak[$h_id]->azpitituluak)) {echo " disabled";} ?>>Editatu hipertranskribapena</button>
					</div>
				</div>
				
				<fieldset>
					<legend><strong>Azpitituluak bistaratu hasieran?</strong></legend>
					<div id="azpitituluak_botoia_div" class="control-group">
						<label class="radio">
							<input id="azpitituluak_bistaratu_bai_<?php echo $h_id; ?>" type="radio" name="azpitituluak_bistaratu_<?php echo $h_id; ?>" value="1"<?php if ($eztabaida->hizkuntzak[$h_id]->azpitituluak_bistaratu == 1) {echo " checked";} ?>>Bai
						</label>
						<label class="radio">
							<input id="azpitituluak_bistaratu_ez_<?php echo $h_id; ?>" type="radio" name="azpitituluak_bistaratu_<?php echo $h_id; ?>" value="0"<?php if ($eztabaida->hizkuntzak[$h_id]->azpitituluak_bistaratu == 0) {echo " checked";} ?>>Ez
						</label>
					</div>
					<div>Hizkuntza honetan azpitituluak hasieran bistaratu behar diren ala ez zehazten da aukera honetan.</div>
				</fieldset>
				
				<fieldset>
					<legend><strong>Azpitituluak bistaratu/ezkutatzeko botoia bai?</strong></legend>
					<div id="azpitituluak_botoia_div" class="control-group">
						<label class="radio">
							<input id="azpitituluak_botoia_bai_<?php echo $h_id; ?>" type="radio" name="azpitituluak_botoia_<?php echo $h_id; ?>" value="1"<?php if ($eztabaida->hizkuntzak[$h_id]->azpitituluak_botoia == 1) {echo " checked";} ?>>Bai
						</label>
						<label class="radio">
							<input id="azpitituluak_botoia_ez_<?php echo $h_id; ?>" type="radio" name="azpitituluak_botoia_<?php echo $h_id; ?>" value="0"<?php if ($eztabaida->hizkuntzak[$h_id]->azpitituluak_botoia == 0) {echo " checked";} ?>>Ez
						</label>
					</div>
					<div>Hizkuntza honetan azpitituluak bistaratu eta ezkutatzeko botoiak agertu behar duen ala ez zehazten da aukera honetan.</div>
				</fieldset>
			</fieldset>
			
			<?php } ?>
			
			<fieldset>
				<legend><strong>Azpitituluak non?</strong></legend>
				<div id="azpitituluak_non_div" class="control-group">
					<label class="radio">
						<input id="azpitituluak_behean" type="radio" name="azpitituluak_non" value="0"<?php if ($eztabaida->azpitituluak_non == 0) {echo " checked";} ?>>Behean
					</label>
					<label class="radio">
						<input id="azpitituluak_goian" type="radio" name="azpitituluak_non" value="1"<?php if ($eztabaida->azpitituluak_non == 1) {echo " checked";} ?>>Goian
					</label>
				</div>
				<div>Azpitituluak modu lehenetsian bideoaren beheko ala goiko aldean agertu behar duten zehazten da aukera honetan.</div>
				<div>Azpitituluak behean daudenean grafismo bat agertzen denean azpitituluak automatikoki gora pasatzen dira eta desagertzean berriz ere behera bueltatzen dira.</div>
			</fieldset>
			
			<div class="control-group text-center">
				<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
				<button type="reset" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
			</div>
		</form>
	</div>
	
	<div class="tab-pane" id="eztabaidak-fitxa-momentuak">
		<fieldset>
			<legend><strong>Momenturik onenak</strong></legend>
			
			<table id="momentuak_taula" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="50">Ordena</th>
						<th>Testua</th>
						<th width="85">
							<a id="gehitu_momentua_botoia" class="btn" href="#editatu_momentua" data-toggle="modal">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$orden_max = orden_max("eztabaidak_momentuak", "fk_elem = " . $edit_id);
						
						foreach($eztabaida->momentuak as $elem) {
					?>
					<tr <?php if ($klassak) { echo current($klassak); } ?>>
						<td>
							<select class="input-mini" name="orden_<?php echo $elem->id; ?>" onchange="javascript:document.location='<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_momentua=<?php echo $elem->id; ?>&bal=' + this.options[this.selectedIndex].value;">
								<option value="0">0</option>
							<?php for ($i = 1; $i <= ($elem->orden == 0 ? $orden_max + 1 : $orden_max); $i++){ ?>
								<option value="<?php echo $i; ?>"<?php echo $i == $elem->orden ? " selected" : ""; ?>><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</td>
						<td id="momentua_testua_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->testua; ?></td>
						<td class="td_aukerak">
							<a href="#editatu_momentua" data-id-momentua="<?php echo $elem->id; ?>" role="button" class="btn editatu_momentua_botoia" data-toggle="modal"><i class="icon-pencil" data-id-momentua="<?php echo $elem->id; ?>"></i></a>
							<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_momentua_id=<?php echo $elem->id; ?>" onclick="javascript: return (confirm ('Seguru momentua ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
						</td>
					</tr>
					<?php if ($klassak && !next ($klassak)) { reset($klassak); } } ?>
				</tbody>
			</table>
		</fieldset>
		
		<div id="editatu_momentua" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editatu_momentua_izenburua_etiketa" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="editatu_momentua_izenburua_etiketa">Editatu momentua</h3>
			</div>
			<form id="editatu_momentua_form" method="post" action="<?php echo $url_base; ?>editatu_momentua">
				<div class="modal-body">
					<fieldset>
						<input type="hidden" name="gorde" value="BAI" />
						<input type="hidden" id="editatu_momentua_id" name="editatu_momentua_id" value="" />
						<input id="editatu_momentua_id_eztabaida" type="hidden" name="editatu_momentua_id_eztabaida" value="<?php echo $edit_id; ?>" />
						<fieldset>
							<legend><strong>Irudiak</strong></legend>
							<div class="control-group">
								<label class="control-label" for="editatu_momentua_irudia">Irudia:</label>
								<div id="editatu_momentua_irudia_div" class="controls">
									<input class="input-xxlarge" name="editatu_momentua_irudia" type="file" id="editatu_momentua_irudia" />
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend><strong>Denborak</strong></legend>
							<div class="control-group">
								<label for="editatu_momentua_hasiera">Hasiera: (hh:mm:ss)</label>
								<input class="input-xlarge" type="text" id="editatu_momentua_hasiera" name="editatu_momentua_hasiera" value="" />
							</div>
										
							<div class="control-group">
								<label for="editatu_momentua_bukaera">Bukaera: (hh:mm:ss)</label>
								<input class="input-xlarge" type="text" id="editatu_momentua_bukaera" name="editatu_momentua_bukaera" value="" />
							</div>
						</fieldset>
						<span id="editatu_momentua_fieldset_edukinontzia"></span>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
					<button id="editatu_momentua_gorde_botoia" class="btn btn-primary" type="submit">Gorde</button>
				</div>
			</form>
		</div>
	</div>
	
	<div class="tab-pane" id="eztabaidak-fitxa-grafismoak">
		<fieldset>
			<legend><strong>Hizlariak aurkezteko grafismoak</strong></legend>
			
			<table id="grafismoak_taula" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="50">Ordena</th>
						<th>Hasiera</th>
						<th>Amaiera</th>
						<th>Hizlaria</th>
						<th width="85">
							<a id="gehitu_grafismoa_botoia" class="btn" href="#editatu_grafismoa" data-toggle="modal">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$orden_max = orden_max("eztabaidak_grafismoak", "fk_elem = " . $edit_id);
						
						foreach($eztabaida->grafismoak as $elem) {
					?>
					<tr <?php if ($klassak) { echo current($klassak); } ?>>
						<td>
							<select class="input-mini" name="orden_<?php echo $elem->id; ?>" onchange="javascript:document.location='<?php echo $url_base . $url_param; ?>&edit_id=<? echo $edit_id; ?>&oid_grafismoa=<?php echo $elem->id; ?>&bal=' + this.options[this.selectedIndex].value;">
								<option value="0">0</option>
							<?php for ($i = 1; $i <= ($elem->orden == 0 ? $orden_max + 1 : $orden_max); $i++){ ?>
								<option value="<?php echo $i; ?>"<?php echo $i == $elem->orden ? " selected" : ""; ?>><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</td>
						<td id="grafismoa_hasiera_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->hasiera_ms; ?></td>
						<td id="grafismoa_bukaera_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->amaiera_ms; ?></td>
						<td id="grafismoa_hizlaria_<?php echo $elem->id; ?>" class="td_klik" data-id-hizlaria="<?php echo $elem->id_hizlaria; ?>"><?php echo $eztabaida->hizlariak[$elem->id_hizlaria]->izena; ?></td>
						<td class="td_aukerak">
							<a href="#editatu_grafismoa" data-id-grafismoa="<?php echo $elem->id; ?>" role="button" class="btn editatu_grafismoa_botoia" data-toggle="modal"><i class="icon-pencil" data-id-grafismoa="<?php echo $elem->id; ?>"></i></a>
							<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_grafismoa_id=<?php echo $elem->id; ?>" onclick="javascript: return (confirm ('Seguru grafismoa ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
						</td>
					</tr>
					<?php if ($klassak && !next($klassak)) { reset($klassak); } } ?>
				</tbody>
			</table>
		</fieldset>
		
		<fieldset>
			<legend><strong>Informazio gehigarrirako esteken grafismoak</strong></legend>
			
			<table id="infoestekak_grafismoak_taula" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Hasiera</th>
						<th>Amaiera</th>
						<th>Esteka</th>
						<th width="85">
							<a id="gehitu_infoesteka_grafismoa_botoia" class="btn" href="#editatu_infoesteka_grafismoa" data-toggle="modal">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($eztabaida->infoesteka_grafismoak as $elem) {
					?>
					<tr <?php if ($klassak) { echo current($klassak); } ?>>
						<td id="infoesteka_grafismoa_hasiera_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->hasiera_ms; ?></td>
						<td id="infoesteka_grafismoa_bukaera_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->amaiera_ms; ?></td>
						<td id="infoesteka_grafismoa_esteka_<?php echo $elem->id; ?>" class="td_klik"><?php echo $elem->hizkuntzak[$hizkuntza['id']]->esteka; ?></td>
						<td class="td_aukerak">
							<a href="#editatu_infoesteka_grafismoa" data-id-infoesteka-grafismoa="<?php echo $elem->id; ?>" role="button" class="btn editatu_infoesteka_grafismoa_botoia" data-toggle="modal"><i class="icon-pencil" data-id-infoesteka-grafismoa="<?php echo $elem->id; ?>"></i></a>
							<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param . "&edit_id=" . $edit_id; ?>&ezab_infoesteka_grafismoa_id=<?php echo $elem->id; ?>" onclick="javascript: return (confirm ('Seguru informazio gehigarrirako estekaren grafismoa ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
						</td>
					</tr>
					<?php if ($klassak && !next($klassak)) { reset($klassak); } } ?>
				</tbody>
			</table>
		</fieldset>
		
		<div id="editatu_grafismoa" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editatu_grafismoa_izenburua_etiketa" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="editatu_grafismoa_izenburua_etiketa">Editatu grafismoa</h3>
			</div>
			<form id="editatu_grafismoa_form" method="post" action="<?php echo $url_base; ?>editatu_grafismoa">
				<div class="modal-body">
					<fieldset>
						<input type="hidden" name="gorde" value="BAI" />
						<input type="hidden" id="editatu_grafismoa_id" name="editatu_grafismoa_id" value="" />
						<input id="editatu_grafismoa_id_eztabaida" type="hidden" name="editatu_grafismoa_id_eztabaida" value="<?php echo $edit_id; ?>" />
							
						<div class="control-group">
							<label for="editatu_grafismoa_hasiera">Hasiera: (hh:mm:ss)</label>
							<input class="input-xlarge" type="text" id="editatu_grafismoa_hasiera" name="editatu_grafismoa_hasiera" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_grafismoa_bukaera">Bukaera: (hh:mm:ss)</label>
							<input class="input-xlarge" type="text" id="editatu_grafismoa_bukaera" name="editatu_grafismoa_bukaera" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_grafismoa_hizlariak">Hizlaria</label>
							<select id="editatu_grafismoa_hizlariak"></select>
						</div>
						
						<span id="editatu_grafismoa_fieldset_edukinontzia"></span>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
					<button id="editatu_grafismoa_gorde_botoia" class="btn btn-primary" type="submit">Gorde</button>
				</div>
			</form>
		</div>
		
		<div id="editatu_infoesteka_grafismoa" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editatu_infoesteka_grafismoa_izenburua_etiketa" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="editatu_infoesteka_grafismoa_izenburua_etiketa">Editatu estekaren grafismoa</h3>
			</div>
			<form id="editatu_infoesteka_grafismoa_form" method="post" action="<?php echo $url_base; ?>editatu_infoesteka_grafismoa">
				<div class="modal-body">
					<fieldset>
						<input type="hidden" name="gorde" value="BAI" />
						<input type="hidden" id="editatu_infoesteka_grafismoa_id" name="editatu_infoesteka_grafismoa_id" value="" />
						<input id="editatu_infoesteka_grafismoa_id_eztabaida" type="hidden" name="editatu_infoesteka_grafismoa_id_eztabaida" value="<?php echo $edit_id; ?>" />
							
						<div class="control-group">
							<label for="editatu_infoesteka_grafismoa_hasiera">Hasiera: (hh:mm:ss)</label>
							<input class="input-xlarge" type="text" id="editatu_infoesteka_grafismoa_hasiera" name="editatu_infoesteka_grafismoa_hasiera" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_infoesteka_grafismoa_bukaera">Bukaera: (hh:mm:ss)</label>
							<input class="input-xlarge" type="text" id="editatu_infoesteka_grafismoa_bukaera" name="editatu_infoesteka_grafismoa_bukaera" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_infoesteka_grafismoa_izenburua">Izenburua</label>
							<input class="input-xlarge" type="text" id="editatu_infoesteka_grafismoa_izenburua" name="editatu_infoesteka_grafismoa_izenburua" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_infoesteka_grafismoa_azalpena">Azalpena</label>
							<input class="input-xlarge" type="text" id="editatu_infoesteka_grafismoa_azalpena" name="editatu_infoesteka_grafismoa_azalpena" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_infoesteka_grafismoa_esteka">Esteka</label>
							<input class="input-xlarge" type="text" id="editatu_infoesteka_grafismoa_esteka" name="editatu_infoesteka_grafismoa_esteka" value="" />
						</div>
						
						<div class="control-group">
							<label for="editatu_infoesteka_grafismoa_mota">Irudia</label>
							<select id="editatu_infoesteka_grafismoa_mota"></select>
						</div>
						
						<span id="editatu_infoesteka_grafismoa_fieldset_edukinontzia"></span>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
					<button id="editatu_infoesteka_grafismoa_gorde_botoia" class="btn btn-primary" type="submit">Gorde</button>
				</div>
			</form>
		</div>
	</div>
</div>