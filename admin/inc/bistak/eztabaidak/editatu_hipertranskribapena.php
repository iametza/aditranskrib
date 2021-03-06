<script type="text/javascript">
    function verif(){
		var patroi_hutsik = /^\s*$/;
        
		return (confirm ("Hipertranskribapenean egindako aldaketak gorde nahi dituzu?"));
	}
    
    $(document).ready(function() {
		var azpitituluak_testuak = [];
		var hizlariak = [];
		
		<?php
		foreach (hizkuntza_idak() as $h_id) {
			echo "azpitituluak_testuak[" . $h_id . "] = '" . $editatu_hipertranskribapena->azpitituluak_testua . "';";
		}
		
		foreach ($editatu_hipertranskribapena->hizlariak as $elem) {
			echo "hizlariak[" . $elem->id . "] = {};";
			echo "hizlariak[" . $elem->id . "].aurrizkia ='" . $elem->aurrizkia . "';";
			echo "hizlariak[" . $elem->id . "].kolorea ='#" . $elem->kolorea . "';";
		}
		?>
        
		$(".parrafo_checkbox").change(function(event) {
			//console.log($(this));
			
			if ($(this).hasClass("tarte_hasiera_da")) {
				
				// Tarte hasiera denez paragrafo hasiera da eta ezin zaio erabiltzaileari aldatzen utzi.
				$(this).prop('checked', true);
			}
		});
		
        function parseSRT(data, tarteak, parrafo_hasierak) {
			var hurrengo_tartea = 0;
			
			var retObj = {
				title: "",
				remote: "",
				data: []
			},
			subs = [],
			i = 0,
			len = 0,
			idx = 0,
			lines,
			time,
			text,
			sub;
			
			// Simple function to convert HH:MM:SS,MMM or HH:MM:SS.MMM to SS.MMM
			// Assume valid, returns 0 on error
			var toSeconds = function( t_in ) {						
				var t = t_in.split( ':' );
				
				try {
					var s = t[2].split( ',' );
					// Just in case a . is decimal seperator
					if ( s.length === 1 ) {
						s = t[2].split( '.' );
					}
					return parseFloat( t[0], 10 )*3600 + parseFloat( t[1], 10 )*60 + parseFloat( s[0], 10 ) + parseFloat( s[1], 10 )/1000;
				} catch ( e ) {
					return 0;
				}
			};
			
			var outputString = "";
			
			// Hipertranskribapena sortzerakoan aplikatu beharreko aukerak (erabiltzaileak zehazturikoak):
			// 		* lineBreaks: Hipertranskribapenaren formatua zehazten du: <span> bakoitzak lerro berri batean joan behar duen ala ez.
			// 		* wordLengthSplit: Hitz bakoitzaren denborak kalkulatzean hitzen luzera kontutan hartu behar den ala ez (gomendatua).
			var lineBreaks = $('#editatu-hipertranskribapena-lerro-jauziak').prop('checked');
			var wordLengthSplit = $('#editatu-hipertranskribapena-hitzen-luzera').prop('checked');
				
			// Hitz bat tarte baten hasiera den ala ez adierazten du.
			var tartearen_hasiera_da = false;
			
			var parrafo_kont = 0;
			
			// Here is where the magic happens
			// Split on line breaks
			lines = data.split( /(?:\r\n|\r|\n)/gm );
			
			len = lines.length;
			
			for( i=0; i < len; i++ ) {
				sub = {};
				text = [];
				
				sub.id = parseInt( lines[i++].replace("\"", ""), 10 );
				
				// Azken errenkada lerro huts bat zenean errorea ematen zuen hau gabe,
				// hurrengo errenkada ez baita existitzen.
				if (!lines[i + 1]) {
					break;
				}
				
				// Split on '-->' delimiter, trimming spaces as well
				time = lines[i++].split( /[\t ]*-->[\t ]*/ );
			
				sub.start = toSeconds( time[0] );
			
				// So as to trim positioning information from end
				idx = time[1].indexOf( " " );
			
				if ( idx !== -1) {
					time[1] = time[1].substr( 0, idx );
				}
			
				sub.end = toSeconds( time[1] );
			
				// Build single line of text from multi-line subtitle in file
				while ( i < len && lines[i] ) {
					text.push( lines[i++] );
				}
			
				// Join into 1 line, SSA-style linebreaks
				// Strip out other SSA-style tags
				sub.text = text.join( "\\N" ).replace( /\{(\\[\w]+\(?([\w\d]+,?)+\)?)+\}/gi, "" );
				
				// Escape HTML entities
				sub.text = sub.text.replace( /</g, "&lt;" ).replace( />/g, "&gt;" );
			
				// Unescape great than and less than when it makes a valid html tag of a supported style (font, b, u, s, i)
				// Modified version of regex from Phil Haack's blog: http://haacked.com/archive/2004/10/25/usingregularexpressionstomatchhtml.aspx
				// Later modified by kev: http://kevin.deldycke.com/2007/03/ultimate-regular-expression-for-html-tag-parsing-with-php/
				sub.text = sub.text.replace( /&lt;(\/?(font|b|u|i|s))((\s+(\w|\w[\w\-]*\w)(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)(\/?)&gt;/gi, "<$1$3$7>" );
				
				//sub.text = sub.text.replace( /\\N/gi, "<br />" ); 	// Jatorrizkoa
				sub.text = sub.text.replace( /\\N/gi, " " );		// Guri ez zaigu komeni hipertranskribapenean <br /> etiketarik agertzerik.
				
				var splitMode = 0;

				
				
				// enhancements to take account of word length
				var swords = sub.text.split(' ');
				var sduration = sub.end - sub.start;
				var stimeStep = sduration/swords.length;

				// determine length of words
				
				var swordLengths = [];
				var swordTimes = [];

				var totalLetters = 0;
				
				for (var si=0, sl=swords.length; si<sl; ++si) {
					totalLetters = totalLetters + swords[si].length;
					swordLengths[si] = swords[si].length;
				}

				var letterTime = sduration / totalLetters;
				var wordStart = 0;
				
				// Momentuko azpititulua tarte baten hasiera den egiaztatuko dugu.
				if (hurrengo_tartea < tarteak.length && tarteak[hurrengo_tartea].hasiera === sub.id) {
					tartearen_hasiera_da = true;
				}
				
				for (var si = 0, sl = swords.length; si < sl; ++si) {
					var wordTime = swordLengths[si]*letterTime;
					var stime;
					
					if (wordLengthSplit) {
						stime = Math.round((sub.start + si*stimeStep) * 1000);
					} else {
						stime = Math.round((wordStart + sub.start) * 1000);
					}
					
					wordStart = wordStart + wordTime;
					
					var stext = swords[si];
					var ssafeText = stext.replace('"', '\\"');
					//outputString += '<span data-ms="'+stime+'" oval="'+ssafeText+'">'+stext+'</span>';
					
					// Hiru puntuak kendu (guztiak kentzen ditu baina desberdindu behar litzateke azpititulua sortzean sortutako eta berezkoen artean)
                    stext = stext.replace("...", "");
					
					// Tartearen hasiera bada <p> bat gehitu behar dugu hasieran
					// eta hizlariaren izena stext-en aurretik.
					if (tartearen_hasiera_da === true) {
						
						//console.log(hurrengo_tartea + ". tartearen hasierako lerroa: " +  sub.id);
						
						outputString += '<p><span data-ms="' + stime + '">' + hizlariak[tarteak[hurrengo_tartea].id_hizlaria].aurrizkia + " " + stext + '</span> ';
						
						tartearen_hasiera_da = false;
						
						// Tartearen hasiera guztiak dira parrafo hasierak.
						parrafo_kont++;
						
					} else {
						
						// Parrafo baten hasiera bada.
						if (parrafo_hasierak[parrafo_kont] === sub.id) {
							
							outputString += '<p><span data-ms="' + stime + '">' + stext + '</span> ';
							
							parrafo_kont++;
							
						} else {
							
							outputString += '<span data-ms="' + stime + '">' + stext + '</span> ';
							
						}
					}					
					
					if (lineBreaks) outputString = outputString + '\n';
				}
				
				// Momentuko azpititulua tarte baten amaiera den egiaztatuko dugu.
				// Hala bada </p> bat gehitu behar dugu.
				if (hurrengo_tartea < tarteak.length && tarteak[hurrengo_tartea].amaiera === sub.id) {
					
					//console.log(hurrengo_tartea + ". tartearen amaierako lerroa: " + sub.id);
					
					outputString = outputString + "</p>";
					
					// Tartearen bukaerara iritsi garenez, hurrengora pasako gara.
					hurrengo_tartea++;
					
				// Hurrengo azpititulua parrafo baten hasiera bada ere </p> bat gehituko dugu.
				} else if ((sub.id + 1) === parrafo_hasierak[parrafo_kont]) {
					
					outputString = outputString + "</p>";
					
				}
			}
			
			return outputString;
		}
		
		$(".rolak").change(function() {
			// Aldatutako select-aren indizea eta hautatutako balioa
			var hautatutako_indizea = $(this).attr("data-indizea");
			var balioa = this.value;
			
			// Tarte baten hasiera baldin bazen bere klasea kendu (bestela ezin da paragrafo hasiera den ala ez aldatu).
			if ($("#rola-p-hasiera-" + hautatutako_indizea).hasClass("tarte_hasiera_da")) {
				$("#rola-p-hasiera-" + hautatutako_indizea).removeClass("tarte_hasiera_da");
			}
			
			// Hautatutako select-aren errenkadako div-aren kolorea aldatu.
			if (balioa > 0) {
				
				// Hizlari bat hautatu bada dagokion kolorea ezarriko dugu.
				$("#rola-kolorea-" + hautatutako_indizea).css("background-color", hizlariak[balioa].kolorea);
				
			// Balioa -1 bada, hau da, erabiltzaileak select-a zurian utzi badu.
			} else if (hautatutako_indizea === '-1') {
				
				$("#rola-kolorea-" + hautatutako_indizea).css("background-color", "#FFFFFF");
			}
		});
        
        $("#editatu-hipertranskribapena-berrezarri").click(function() {
            window.location.href = "<?php echo $url_base . "editatu-hipertranskribapena&edit_id=" . $editatu_hipertranskribapena->id_eztabaida . "&h_id=" . $editatu_hipertranskribapena->h_id; ?>";
        });
        
        $("#editatu-hipertranskribapena-form").submit(function() {
			var hasierako_indizea = 0; // Tarte baten hasierako indizea. 0ak tarte berri bat hasi behar dugula adierazten du.
			var momentuko_hizlariaren_id = 0;
			var tarteak = [];
			var tartea = {};
			
			var tarte_kont = 0;
			
			var parrafo_hasierak = [];
			
			$(".rolak").each(function() {
				var indizea = parseInt($(this).attr("data-indizea"), 10);
				tartea = {};
				
				// hasierako_indizea 0 bada lehenengo tartearen hasieran gaude.
				if (hasierako_indizea === 0) {
					hasierako_indizea = indizea;
					momentuko_hizlariaren_id = parseInt($(this).val(), 10);
				// Bestela, tartea amaitu den egiaztatuko dugu.
				} else {
					// Hizlaria aldatu bada tartea aurreko indizean amaitu da
					// eta berri batean gaude.
					if (parseInt($(this).val(), 10) !== -1 && momentuko_hizlariaren_id !== parseInt($(this).val(), 10)) {
						// Bukatu den tartearen balioak gordeko ditugu.
						tartea["hasiera"] = hasierako_indizea;
						tartea["amaiera"] = indizea - 1;
						tartea["id_hizlaria"] = momentuko_hizlariaren_id;
						
						tarteak.push(tartea);
						
						//console.log(tarteak);
				
						// Eta tarte berriaren hasierako balioak gordeko ditugu.
						hasierako_indizea = indizea;
						momentuko_hizlariaren_id = parseInt($(this).val(), 10);
					}
				}				
			});
			
			// Hipertranskribapenaren amaieran azken tartea itxi
			tartea = {};
			
			tartea["hasiera"] = hasierako_indizea;
			tartea["amaiera"] = $(".rolak").length;
			tartea["id_hizlaria"] = momentuko_hizlariaren_id;
			
			// Erabiltzaileak gutxienez hizlari bat zehaztu badu azken tartea gehituko dugu tarteen zerrendara.
			if (tartea["id_hizlaria"] !== -1) {
				tarteak.push(tartea);
			// Bestela mezu bat erabiliko dugu hizlaririk ez duela zehaztu adierazteko.
			} else {
				alert("Azpitituluko tarteak zein hizlariri dagozkion adierazi behar duzu.\nBestela, bilaketa eta estatistikek ez dute funtzionatuko.");
			}
			
			$(".parrafo_checkbox").each(function() {
				
				// Azpitituluaren indizea eskuratu.
				var indizea = parseInt($(this).attr("data-indizea"), 10);
				
				// Tarte baten hasiera bada parrafo hasiera ere bada.
				if (tarteak[tarte_kont] && tarteak[tarte_kont]["hasiera"] === indizea) {
					
					parrafo_hasierak.push(indizea);
					tarte_kont++;
					
				// Erabiltzaileak parrafo hasiera bezala markatu badu.
				} else if ($(this).prop('checked')) {
					
					// parrafo_hasierak arrayan gorde gero erabiltzeko.
					parrafo_hasierak.push(indizea);
				}
			});
			
			var srt = azpitituluak_testuak[1];
			
			var ht = parseSRT(srt, tarteak, parrafo_hasierak);
			//console.log(ht);
        
			$.post("<?php echo $url_base . "editatu-hipertranskribapena&edit_id=" . $editatu_hipertranskribapena->id_eztabaida . "&h_id=" . $editatu_hipertranskribapena->h_id; ?>", {
				"gorde": "gorde",
				"id_eztabaida": <?php echo $editatu_hipertranskribapena->id_eztabaida; ?>,
				"id_hizkuntza": <?php echo $editatu_hipertranskribapena->h_id; ?>,
				"hipertranskribapena": ht,
				"tarteak": tarteak,
				"parrafo_hasierak": parrafo_hasierak
			}, function(data) {
				//console && console.log(data);
				
				if (data["arrakasta"]) {
                    // Behar bezala gorde dugunez, eztabaidaren orrira berbideratu.
                    window.location.href = "<?php echo $url_base . "form/" . $url_param . "&edit_id=" . $editatu_hipertranskribapena->id_eztabaida; ?>";
				} else {
					alert("Errore bat gertatu da datu-basean momentuaren datuak gordetzean:\n" + data["mezua"]);
				}
			}, "json");
            
            return false;
		});
    });
</script>

<div class="navbar">
    <div class="navbar-inner">
        <div class="brand"><a href="<?php echo $url_base; ?>eztabaidak">Eztabaidak</a> > <a href="<?php echo $url_base . "form/" . $url_param . "&edit_id=" . $editatu_hipertranskribapena->id_eztabaida; ?>"><?php echo $editatu_hipertranskribapena->eztabaidaren_izenburua; ?></a> > Hipertranskribapenaren editorea</div>
        
        <div class="pull-right">
            <a class="btn" href="<?php echo $url_base . "form/" . $url_param . "&edit_id=" . $editatu_hipertranskribapena->id_eztabaida; ?>"><i class="icon-circle-arrow-left"></i>&nbsp;Atzera</a>
        </div>
    </div>
</div>

<div class="formularioa">
	<form id="editatu-hipertranskribapena-form" name="editatu-hipertranskribapena-form" method="post" action="<?php echo $url_base . "editatu-hipertranskribapena&edit_id=" . $editatu_hipertranskribapena->id_eztabaida . "&h_id=" . $h_id; ?>" class="form-horizontal" enctype="multipart/form-data" onsubmit="javascript: return verif();">
        <input type="hidden" name="gorde" value="BAI" />
        <div>
			<fieldset>
				<legend><strong>Aukerak</strong></legend>
				
				<p><input name="editatu-hipertranskribapena-lerro-jauziak" id="editatu-hipertranskribapena-lerro-jauziak" type="checkbox" checked>Lerro-jauziak sartu hipertranskribapenean</input></p>
				<p><input name="editatu-hipertranskribapena-hitzen-luzera" id="editatu-hipertranskribapena-hitzen-luzera" type="checkbox" checked>Hitzen luzera kontutan hartu denborak kalkulatzean (gomendatua)</input></p>
			</fieldset>
			<fieldset>
                <legend><strong>Hipertranskribapena: <?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $editatu_hipertranskribapena->h_id); ?></strong></legend>
				<table class="table table-bordered table-hover">
					<tr>
						<th>&lt;p&gt;</th>
						<th>Rol</th>
						<th>Zbk</th>
						<th>Denbora</th>
						<th>Testua</th>
					</tr>
				<?php
				if (is_file ("../" . $editatu_hipertranskribapena->azpitituluak)) {
					// Fitxategia ireki irakurtzeko
					$handle = fopen("../" . $editatu_hipertranskribapena->azpitituluak, "r");
					
					// Azpitituluaren zein ataletan gauden jakiteko aldagaia da $kontagailua.
					// 0 (Zenbagarren azpitituluan gauden): 1
					// 1 (Hasierako eta bukaerako denbora): 00:00:01,500 -> 00:00:03,500
					// 2: Azpitituluaren lehen lerroa
					// 2 + n: azpitituluaren enegarren lerroa (baldin badago)
					// 3 (Azpitituluak lerro bakarra badu) edo 2 + azpitituluaren lerro kopurua + 1 (lerro bat baino gehiagoko azpitituluak): hurrengo azpitituluaren aurretik dagoen lerro hutsa. \n (LINUX) edo \r\n (WINDOWS) edo \r (MACOSX)
					$kontagailua = 0;
					
					// Zenbagarren tartean gauden adierazten du.
					$tarte_kont = 0;
					
					// Zenbagarren parrafoan gauden adierazten du.
					$p_kont = 0;
					
					// Tarteen kolore lehenetsia txuria da. DBan tarterik ez badago denak txuriz margotuko ditugu.
					$kolorea = "#ffffff";
					
					$tarte_hasiera_klasea = "";
					
					// Fitxategia irekitzea lortu badugu
					if ($handle) {
						// Lerroak banan bana pasako ditugu
						while (($line = fgets($handle)) !== false) {					
							if ($kontagailua == 0) {
								echo "<tr>";
								
								// Momentuko azpititulu zenbakia tarte baten hasiera bada dagokion kolorea erabiliko dugu beste tarte bat hasi arte.
								if ($editatu_hipertranskribapena->tarteak[$tarte_kont]->indizea_hasiera == trim($line)) {
									
									$kolorea = "#" . $editatu_hipertranskribapena->hizlariak[$editatu_hipertranskribapena->tarteak[$tarte_kont]->id_hizlaria]->kolorea;
									
									// Tarte baten hasiera diren checkbox-ei klase bat jarriko diegu, erabiltzaileak desautatzerik izan ez dezan. Ikusi .parrafo_checkbox-en change gertaera.
									$tarte_hasiera_klasea = " tarte_hasiera_da";
								} else {
									$tarte_hasiera_klasea = "";
								}
								
								// Parrafo baten hasiera bada, checked jarri behar da.
								if ($editatu_hipertranskribapena->parrafo_hasierak[$p_kont] == trim($line)) {
									
									echo "<td id='rola-kolorea-" . trim($line) . "' style='width: 20px; background: " . $kolorea . "'><input id='rola-p-hasiera-" . trim($line) . "' class='parrafo_checkbox" . $tarte_hasiera_klasea . "' type='checkbox' checked='' value='1' name=='rola-p-hasiera-" . trim($line) . "' data-indizea='" . trim($line) . "'></td>";
									
									$p_kont++;
									
								} else {
									
									echo "<td id='rola-kolorea-" . trim($line) . "' style='width: 20px; background: " . $kolorea . "'><input id='rola-p-hasiera-" . trim($line) . "' class='parrafo_checkbox" . $tarte_hasiera_klasea . "' type='checkbox' value='1' name=='rola-p-hasiera-" . trim($line) . "' data-indizea='" . trim($line) . "'></td>";
									
								}
								
								echo "<td>";
								echo "<select id='rola-selecta-" . trim($line) . "' class='rolak' data-indizea=" . trim($line) . ">";
								echo "<option value='-1'>&nbsp;</option>";
								
								foreach ($editatu_hipertranskribapena->hizlariak as $elem) {
									// select-ean tarte horri dagokion hizlaria hautatuko dugu.
									if ($editatu_hipertranskribapena->tarteak[$tarte_kont]->id_hizlaria == $elem->id) {
										echo "<option value='" . $elem->id . "' selected>" . $elem->izena . "</option>";
									} else {
										echo "<option value='" . $elem->id . "'>" . $elem->izena . "</option>";
									}
								}
								
								// Momentuko azpititulu zenbakia tarte baten amaiera bada tarte_kont kontagailua aldatuko dugu.
								if ($editatu_hipertranskribapena->tarteak[$tarte_kont]->indizea_amaiera == trim($line)) {
									
									$tarte_kont++;
								}
								
								echo "</select>";
								echo "</td>";
								echo "<td>" . $line . "</td>";
								$kontagailua++;
							} else if ($kontagailua == 1) {
								echo "<td>" . $line . "</td>";
								$kontagailua++;
							} else if ($kontagailua == 2) {
								echo "<td>" . $line;
								$kontagailua++;
							} else if ($kontagailua >= 3) {
								if ($line == "\n" || $line == "\r\n" || $line == "\r") {
									echo "</tr></td>";
									$kontagailua = 0;
								} else {
									echo "<br />" . $line;
									$kontagailua++;
								}
							}
						}
					} else {
						// error opening the file.
						echo "Errorea fitxategia irekitzean";
					}
					
					// Fitxategia itxi
					fclose($handle);
				}
				?>
				</table>
			</fieldset>
		</div>
        <div class="control-group text-center">
			<button type="submit" class="btn"><i class="icon-edit"></i>&nbsp;Gorde</button>
			<button id="editatu-hipertranskribapena-berrezarri" type="button" class="btn"><i class="icon-repeat"></i>&nbsp;Berrezarri</button>
		</div>
	</form>
</div>