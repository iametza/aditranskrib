<!doctype html>
<html lang="eu">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="eu" />
		<meta name="description" content="Aditranskrib" />
		<link rel="author" href="mailto:info@iametza.com" title="iametza interaktiboarekin harremanetan jarri">
		<meta name="copyright" content="2013 iametza interaktiboa"/>
		<meta name="keywords" content="Aditranskrib" />
		<meta name="Distribution" content="Global"/>
		<meta name="Revisit" content="7 days"/>
		<meta name="Robots" content="All"/>
		
		<!-- Chrome-ren motorra erabiltzeko instalatuta dagoen kasueta IE-->
		<meta http-equiv="X-UA-Compatible" content="chrome=1">

		<title>Aditranskrib</title>

		<!--[if IE]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<!--[if lte IE 7]>
			<script src="js/IE8.js" type="text/javascript"></script><![endif]-->
		
		<!-- CSS -->
		<!-- reset css -->
		<link href="<?php echo URL_BASE; ?>css/reset.css" rel="stylesheet" type="text/css" />
		<!-- main css -->
		<link href="<?php echo URL_BASE; ?>css/itxura.css" rel="stylesheet" type="text/css" />
		
		<!-- <link href="<?php echo URL_BASE; ?>css/eztabaida.css" rel="stylesheet" type="text/css"> -->
		<link href="<?php echo URL_BASE; ?>skin/eztabaida-skin.css" rel="stylesheet" type="text/css">
		
		<link href="<?php echo URL_BASE; ?>css/popcorn.lowerThird.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo URL_BASE; ?>css/infoestekak.css" rel="stylesheet" type="text/css" />
		
		<?php if ($eztabaida->momenturik_onenak_bai == 0 || $eztabaida->bilaketa_bai == 0 || $eztabaida->gazta_bai == 0 || $eztabaida->partekatu_bai == 0 || $eztabaida->txertatu_bai == 0 || $eztabaida->lizentzia_bai == 0 || $eztabaida->barrak_bai == 0) { ?>
		<style>
		<?php } ?>
		
		<?php if ($eztabaida->momenturik_onenak_bai == 0) { echo "#momenturik_onenak_kaja { display: none; }"; } ?>
		<?php if ($eztabaida->bilaketa_bai == 0) { echo "#bilaketa_kaja { display: none; }"; } ?>
		<?php if ($eztabaida->gazta_bai  == 0) { echo "#pie-results, #pie-chart { display: none; }"; } ?>
		<?php if ($eztabaida->partekatu_bai == 0) { echo "#partekatu_kaja { display: none; }"; } ?>
		<?php if ($eztabaida->txertatu_bai == 0) { echo "#txertatu_kaja { display: none; }"; } ?>
		<?php if ($eztabaida->lizentzia_bai == 0) { echo "#lizentzia_kaja { display: none; }"; } ?>
		<?php if ($eztabaida->barrak_bai == 0) { echo "#chart { display: none; }"; } ?>
		
		<?php if ($eztabaida->momenturik_onenak_bai == 0 || $eztabaida->bilaketa_bai == 0 || $eztabaida->hitzak_guztira_bai == 0 || $eztabaida->partekatu_bai == 0 || $eztabaida->txertatu_bai == 0 || $eztabaida->lizentzia_bai == 0 || $eztabaida->barrak_bai == 0) { ?>
		</style>
		<?php } ?>
		
		<script>
			var eztabaida = {
				url_bideoa: '<?php echo $eztabaida->url_bideoa; ?>',
				//url_bideoa: 'http://vimeo.com/43595116',
				//url_bideoa: '<?php echo URL_BASE; ?>bideoak/eztabaidak/urkumintegiHD.mp4',
				path_hipertranskribapena: '../<?php echo $eztabaida->path_hipertranskribapena; ?>',
				hipertranskribapena: '<?php echo $eztabaida->hipertranskribapena; ?>',
				hipertranskribapena_testua: <?php echo $eztabaida->hipertranskribapena_testua; ?>,
				azpitituluak: '<?php echo URL_BASE . $eztabaida->path_azpitituluak . $eztabaida->azpitituluak; ?>',
				azpitituluak_bistaratu: <?php echo $eztabaida->azpitituluak_bistaratu; ?>,
				azpitituluak_botoia: <?php echo $eztabaida->azpitituluak_botoia; ?>,
				posterra: '<?php if ($eztabaida->posterra != "") { echo URL_BASE . $eztabaida->path_posterra . $eztabaida->posterra; } else { echo ""; }; ?>',
				hashTag: '<?php echo $eztabaida->hash_tag; ?>',
				fb_izenburua: '<?php echo $eztabaida->fb_izenburua; ?>',
				bilaketa_kaxa_testua: '<?php echo $eztabaida->bilaketa_kaxa_testua; ?>',
				
				hizlariak: [<?php for ($i = 0; $i < count($eztabaida->hizlariak); $i++) { ?>
				{
					indizea: <?php echo $i; ?>,
					izena: '<?php echo $eztabaida->hizlariak[$i]->izena; ?>',
					bilagarria: <?php echo $eztabaida->hizlariak[$i]->bilagarria; ?>,
					aurrizkia: '<?php echo $eztabaida->hizlariak[$i]->aurrizkia; ?>',
					kolorea: '#<?php echo $eztabaida->hizlariak[$i]->kolorea; ?>',
					gazta_etiketa: '<?php echo $eztabaida->hizlariak[$i]->gazta_etiketa; ?>',
					grafismoa_deskribapena: '<?php echo $eztabaida->hizlariak[$i]->grafismoa_deskribapena; ?>',
					grafismoa_esteka: '<?php echo $eztabaida->hizlariak[$i]->grafismoa_esteka; ?>',
					grafismoa_logoa: '<?php echo  URL_BASE . $eztabaida->hizlariak[$i]->path_grafismoa_irudia . $eztabaida->hizlariak[$i]->grafismoa_irudia; ?>'
				}<?php if ($i < count($eztabaida->hizlariak) - 1) {echo ',';}; ?>
				<?php } ?>],
				
				hizlari_bilagarriak: <?php echo json_encode($eztabaida->hizlari_bilagarriak); ?>,
				
				grafismoak: [<?php for ($i = 0; $i < count($eztabaida->grafismoak); $i++) { ?>
				{
					indizea: <?php echo $i; ?>,
					hasiera: <?php echo $eztabaida->grafismoak[$i]->hasiera; ?>,
					amaiera: <?php echo $eztabaida->grafismoak[$i]->amaiera; ?>,
					indizea_hizlaria: <?php echo $eztabaida->grafismoak[$i]->indizea_hizlaria; ?>
				}<?php if ($i < count($eztabaida->grafismoak) - 1) {echo ',';}; ?>
				<?php } ?>],
				
				infoak: [<?php for ($i = 0; $i < count($eztabaida->infoak); $i++) { ?>
				{
					indizea: <?php echo $i; ?>,
					hasiera: <?php echo $eztabaida->infoak[$i]->hasiera; ?>,
					amaiera: <?php echo $eztabaida->infoak[$i]->amaiera; ?>,
					izenburua: '<?php echo $eztabaida->infoak[$i]->izenburua; ?>',
					azalpena: '<?php echo $eztabaida->infoak[$i]->azalpena; ?>',
					esteka: '<?php echo $eztabaida->infoak[$i]->esteka; ?>',
					path_irudia: '<?php echo URL_BASE . $eztabaida->infoak[$i]->path_irudia; ?>'
				}<?php if ($i < count($eztabaida->infoak) - 1) {echo ',';}; ?>
				<?php } ?>],
				
				swfPath: '<?php echo URL_BASE . $eztabaida->path_swf; ?>',
				barrak_testu_kolorea: '#<?php echo $eztabaida->barrak_testu_kolorea; ?>',
				gazta_testu_kolorea: '#<?php echo $eztabaida->gazta_testu_kolorea; ?>',
				gazta_marra_kolorea: '#<?php echo $eztabaida->gazta_marra_kolorea; ?>'
			}
		</script>
	</head>
	<body>
		<h1 style="display:none;">Aditranskrib</h1> <!-- Title-aren berdina -->

		<!-- Head -->
		<header class="burua"><a href="#" title="hasierara itzuli"><img src="<?php echo URL_BASE; ?>img/head.jpg" alt="Aditranskrib" width="960" height="90"></a></header>
		<!-- /Head -->

		<!-- Zutabeak -->
		<div class="zutabeak">
			<!-- kol1 -->
			<div class="kol1_<?php if ($eztabaida->zutabea_non == 0) {echo "esk";} else if ($eztabaida->zutabea_non == 1) {echo "ezk";} else {echo "osoa";}; ?>">
				<h2><?php echo $eztabaida->izenburua; ?></h2>
				
				<?php if($eztabaida->azpitituluak_botoia == 1) {
					
					$azpitituluak_botoia_katea = '<button id="azpitituluak-botoia"';
					
					if($eztabaida->zutabea_non == 2) {
						$azpitituluak_botoia_katea = $azpitituluak_botoia_katea . ' class="azpitituluak-botoia-720"';
					}
					
					$azpitituluak_botoia_katea = $azpitituluak_botoia_katea . '>Azpitituluak ';
					
					if($eztabaida->azpitituluak_bistaratu == 1) {
						$azpitituluak_botoia_katea = $azpitituluak_botoia_katea . 'ON';
					} else {
						$azpitituluak_botoia_katea = $azpitituluak_botoia_katea . 'OFF';
					}
					
					$azpitituluak_botoia_katea = $azpitituluak_botoia_katea . '</button>';
					
					echo $azpitituluak_botoia_katea;
				} ?>
				
				<!-- transcript content -->
				<div id="transcript" class="middle col">
					
					<!-- video display (when required) -->
					<div id="media-content" class="media-hldr">
						<div id="jp_container_1" class="jp-video jp-video-360p">
							<?php if ($eztabaida->posterra != "") { echo "<img id='posterra' src='" . URL_BASE . $eztabaida->path_posterra . $eztabaida->posterra . "' />"; } ?>
							<div style="width: 720px; height: 405px;" id="jquery_jplayer_1" class="jp-jplayer">
								<div id="infoesteka"></div>
								<div id="bideoa-azpitituluak" style="<?php if ($eztabaida->azpitituluak_bistaratu == 0) {echo "display: none;";}; ?>" class="<?php if ($eztabaida->azpitituluak_non == 1) {echo "bideoa-azpitituluak-goian";} else if ($eztabaida->azpitituluak_non == 0) {echo "bideoa-azpitituluak-behean";}; ?>"></div>
							</div>
							<div class="jp-gui">
								<div class="jp-video-play">
									<span class="jp-video-play-icon"><img src="<?php echo URL_BASE; ?>img/eztabaidak/video-play.png"></span>
								</div>
								
								<div class="jp-video-busy" style="display:none">
									<span class="jp-video-busy-icon"><img src="<?php echo URL_BASE; ?>img/eztabaidak/ajax-loader.gif"></span>
								</div>
								
								<div class="jp-quality-ctrl" style="display: none;">
									<ul>
										<?php
											for ($i = 0; $i < count($eztabaida->bideo_kalitateak); $i++) {
												echo "<li><a class='quality-switch' q='" . $eztabaida->bideo_kalitateak[$i] . "' href='#'>" . get_dbtable_field_by_id_hizkuntza("eztabaidak_bideoak_kalitateak", "izen_laburra", $eztabaida->bideo_kalitateak[$i], $hizkuntza["id"]) . "</a></li>";
											}
										?>
									</ul>
								</div>
								
							</div><!--end jp-gui-->
						</div><!--end jp_container_1-->
					</div><!-- end media_content -->
					
					<div id="fade-top" class="fader"></div>
					<div id="transcript-content" class="body testua row scroll-y<?php if ($eztabaida->zutabea_non == 2) {echo " transkribapena-720";}; ?>"></div>
					
					<div class="footer row" style="display:none">
						<p></p>
						<div id="chart"></div>
					</div>
					
					<div id="fade-bot" class="fader"></div>
					
					<div class="mini-footer row<?php if ($eztabaida->zutabea_non == 2) {echo " transkribapena-720";}; ?>">
						<b>☝</b> Klikatu edo ukitu hipertranskribapeneko hitz bat eztabaidaren une horretara joateko.
					</div>
				</div><!-- end transcript -->
			</div><!-- /kol1 -->
	
			<!-- kol2 -->  
			<aside class="kol2_<?php if ($eztabaida->zutabea_non == 0) {echo "ezk";} else if ($eztabaida->zutabea_non == 1) {echo "esk";} else {echo "ezkutuan";}; ?>">
				<div id="laguntza_kaja">
					<a href="<?php echo URL_BASE; ?>eztabaida/laguntza">Laguntza</a>
				</div>
				<div id="momenturik_onenak_kaja" class="kaja">
					<h3>
						<span>Momenturik onenak</span>
					</h3>
					<div class="thumb-hldr">
						<?php for ($i = 0; $i < count($eztabaida->momentuak); $i++) { ?>
							<?php if ($eztabaida->momentuak[$i]->irudia != "") { ?>
						<a class="thumb-link" href="#" data-start="<?php echo $eztabaida->momentuak[$i]->start_ms; ?>" data-end="<?php echo $eztabaida->momentuak[$i]->end_ms; ?>"><img src="<?php echo URL_BASE . $eztabaida->momentuak[$i]->path_irudia . $eztabaida->momentuak[$i]->irudia; ?>"></a>
						<p class="thumb-quote"><?php echo $eztabaida->momentuak[$i]->testua; ?><small> [<?php echo $eztabaida->momentuak[$i]->iraupena; ?>]</small></p>
							<?php } else { ?>
						<p class="thumb-quote"><a class="thumb-link" href="#" data-start="<?php echo $eztabaida->momentuak[$i]->start_ms; ?>" data-end="<?php echo $eztabaida->momentuak[$i]->end_ms; ?>"><?php echo $eztabaida->momentuak[$i]->testua; ?></a><small> [<?php echo $eztabaida->momentuak[$i]->iraupena; ?>]</small></p>
							<?php } ?>
						<?php } ?>
					</div>
				</div>

				<div id="bilaketa_kaja" class="kaja">
					<h3 id="pieTitle">
						<span>Hitzak guztira</span>
					</h3>
					<div id="pie-results">
						<table>
						<?php for ($i = 0; $i < count($eztabaida->hizlariak); $i++) { ?>
						<?php 	if ($eztabaida->hizlariak[$i]->bilagarria == 1) { ?>
						<tr><td id='pie-results-kolorea-<?php echo $i; ?>' style="width: 20px; background-color: #<?php echo $eztabaida->hizlariak[$i]->kolorea; ?>"></td><td id='pie-results-izen-laburra-<?php echo $i; ?>'><?php echo $eztabaida->hizlariak[$i]->izen_laburra; ?></td><td id='pie-results-kopurua-<?php echo $i; ?>'>-</td></tr>
						<?php 	} ?>
						<?php } ?>
						</table>
					</div>
					<!--<div id="pie-talk"></div>-->
					<div id="pie-chart"><span>Loading ...</span></div>
					<form>
						<p id="search-box"><input class="search-str" id="searchStr" value="" type="text"><input id="search-btn" value="Bilatu" type="button"></p>
					</form>
				</div>
				
				<div id="partekatu_kaja" class="kaja">
					<h3>
						<span>Partekatu</span>
					</h3>
					<div class="share-snippet">Hautatu hipertranskribapeneko zati bat sare sozialetan partekatzeko.</div>
					<div class="social-hldr">
						<div id="tweet-like"></div>
						<a id="fb-link" style="display:none" href="" target="_blank"><img src="<?php echo URL_BASE; ?>img/eztabaidak/facebook.png"></a>
					</div>
				</div>
				
				<div id="txertatu_kaja" class="kaja">
					<h3>Txertatu</h3>
					<div id="txertatu-azalpena">Eztabaida hau zure webgunean txertatu nahi baduzu erabili kode hau:</div>
					<div id="txertatu-kodea-div">
						<textarea id="txertatu-kodea"><?php echo htmlspecialchars("<iframe src='" . URL_BASE . "eztabaida/eae-2012-mintegi-urkullu' height='980' width='960' frameborder='0' scrolling='no'></iframe>"); ?></textarea>
					</div>
				</div>
				
				<div id="babeslea-kaja" class="kaja">
					<h3>Babeslea</h3>
					<div>
						<a href="http://www.gipuzkoa.net" target="_blank"><img id="babeslea-kaja-irudia" src="<?php echo URL_BASE; ?>/img/logoak/aldundi1.jpg"></a>
					</div>
				</div>
				
				<!-- <div id="corner-text" class="footer row" style="display:none">
					<p>Click on the bars to the left to watch the video segment where your keyword is mentioned by each of the candidates.</p>
				</div> -->
				
				<div class="mini-footer row"></div>
				
				<div id="lizentzia_kaja">
					<a href="<?php echo $eztabaida->url_lizentzia; ?>" target="_blank"><img src="<?php echo URL_BASE . $eztabaida->path_lizentzia . $eztabaida->lizentzia; ?>"></a>
				</div>
			</aside><!-- /kol2 -->
			<div class="garbitu"></div>
		</div>
		<!-- /Zutabeak -->
		
		<!-- Oina -->
		<footer class="oina">
			<div class="herriak">&copy; 2013 iametza interaktiboa  ∙ Zirkuitu ibilbidea 2, 1 pabilioia (Gipuzkoa) LASARTE-ORIA 20160 <br>
			<a href="#">CSS</a> | <a href="#">HTML5</a> | <a href="#">WAIA</a> | <a href="#">Lege oharra</a> | <a href="#">Irisgarritasuna</a> | <a href="#">Kontaktua</a></div>
		</footer>
		<!-- /Oina -->
		
		<div class="kopi">
			<p><a href="http://www.argia.com/"><img src="<?php echo URL_BASE; ?>img/eztabaidak/argia.png" width="125" height="45" alt="argia"></a><a href="http://www.iametza.com/"><img src="<?php echo URL_BASE; ?>img/eztabaidak/iametza.png" width="304" height="45" alt="iametza interaktiboa"></a><a href="http://www.mozillaopennews.org/"><img src="<?php echo URL_BASE; ?>img/eztabaidak/mozilla.png" width="196" height="45" alt="mozilla"></a></p>
		</div>

		<!--[if lt IE 9]>
		<script type="text/javascript" src="js/sizzle.js"></script>
		<script type="text/javascript" src="js/es5-shim.min.js"></script>
		<script type="text/javascript" src="js/popcorn.ie8.js"></script> 
		<![endif]-->

		<!-- combine these -->
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery-1.10.2.min.js"></script> 
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.scrollTo.js"></script>

		<script type="text/javascript" src="<?php echo URL_BASE; ?>js/d3.v3.min.js" charset="utf-8"></script> 
		
		<!-- use same version of jQuery as holding page does - that way it should be cached -->
		<!-- minify these -->
		<!-- <script type="text/javascript" src="<?php echo URL_BASE; ?>js/popcorn-ie8.min.js"></script> -->
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/popcorn.js"></script>
		
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/wrappers/common/popcorn._MediaElementProto.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/wrappers/youtube/popcorn.HTMLYouTubeVideoElement.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/wrappers/vimeo/popcorn.HTMLVimeoVideoElement.js"></script>
		
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/modules/player/popcorn.player.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/modules/parser/popcorn.parser.js"></script>
		
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/plugins/code/popcorn.code.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/plugins/subtitle/popcorn.subtitle.js"></script>
		
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js_20140219/parsers/parserSRT/popcorn.parserSRT.js"></script>
		
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js/popcorn.transcript.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js/popcorn.lowerThird.js"></script>
		
		<!--<script type="text/javascript" src="js/jquery.bbq.js"></script>-->  
		<!--<script type="text/javascript" src="js/iemobile-fix.js"></script>-->
		<script type="text/javascript" src="<?php echo URL_BASE; ?>js/eztabaida.js"></script>
	</body>
</html>
