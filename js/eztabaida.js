/*
 * Dual licensed under the MIT and GPL licenses.
 *  - http://www.opensource.org/licenses/mit-license.php
 *  - http://www.gnu.org/copyleft/gpl.html
 *
 * Authors: Mark Boas, Mark J Panaghiston
 *
 * For: Al-Jazeera and OpenNews
 *
 * Date: 4th October 2012
 */

$(document).ready(function(){
	var pop;
	
	// Zenbat barra bistaratu behar diren barren grafikoan.
	var bars;
	
	// Bilaketa bat egiten denean barren grafikoa erakusteko behar diren datuak array honetan gordetzen dira.
	// Ikusi #search-btn botoiaren click gertaera.
	var data;
	
	// Bideoa minutu kopuru hau baino luzeagoa bada barrek 5 minutuko zabalera izango dute. Motzagoa bada 1 minutukoa.
	var denbora_muga = 20;
	
	// Bideoa youtube-koa bada kontrolak bistan jarriko ditugu. Besterik adierazten ez bada popcorn-ek controls=0 erabiltzen du bestela.
	// vimeo-ko bideoetan APIak ez du kontrolak ezkutatzeko aukerarik ematen. Bestela, beti gure kontrol propioak erabiltzea litzateke beste aukera bat.
	if (eztabaida.url_bideoa.indexOf("youtube") != -1) {
		pop = Popcorn.smart( "#jquery_jplayer_1", eztabaida.url_bideoa + "&controls=2" );
	} else {
		pop = Popcorn.smart( "#jquery_jplayer_1", eztabaida.url_bideoa );
	}
	
	/*
	 *************
	 * Funtzioak *
	 *************
	 */
	
	function initTranscript(p) {
		//console.log("initTranscript in "+(new Date()-startTimer));
		$("#transcript-content span").each(function(i) {  
			// doing p.transcript on every word is a bit inefficient - wondering if there is a better way
			p.transcript({
				time: $(this).attr(dataMs) / 1000, // seconds
				futureClass: "transcript-grey",
				target: this,
				onNewPara: function(parent) {
					$("#transcript-content").stop().scrollTo($(parent), 800, {axis:'y',margin:true,offset:{top:0}});
				}
			});  
		});
		//console.log("initTranscript out "+(new Date()-startTimer));
	}
	
	function drawBarChart(data) {
		
		// Bideoaren iraupena minututan (goruntz borobilduta)
		var iraupena_min = Math.ceil(pop.duration() / 60);
		
		// Bideoaren iraupena minututan 5 minutuko tartera goruntz borobilduta.
		var iraupena_min_bost = 5 * Math.ceil(iraupena_min / 5);
		
		// Grafikoaren zabalera
		var width = 720;

		$('#chart').empty();
		var barWidth = (width / data.length) - 4;
		//console.log("barWidth: " + barWidth);
		var height = 70;
		var bottomPadding = 20;
		
		var hizlari_bilagarri_kop = eztabaida.hizlari_bilagarriak.length;
		
		var colorlist = [];
		
		//if ($('#repCb:checked').val()) {
		//	colorlist[0]="#C2DC01";
		//}
		
		//if($('#demCb:checked').val()) {
		//	colorlist[1]="#666666";	
		//}
		
		for (var i = 0; i < hizlari_bilagarri_kop; i++) {
			colorlist[i] = eztabaida.hizlariak[eztabaida.hizlari_bilagarriak[i]].kolorea;
		}
		
		//console.log(colorlist);
		
		var x = d3.scale.linear().domain([0, data.length]).range([0, width]);
		var y = d3.scale.linear().domain([0, d3.max(data, function(datum) { return datum.s; })]).
		  rangeRound([0, height]);
		
		// add the canvas to the DOM
		var barDemo = d3.select("#chart").
		  append("svg:svg").
		  attr("width", width).
		  attr("height", height+bottomPadding);
		
		barDemo.selectAll("rect").
		  data(data).
		  enter().
		  append("svg:rect").
		  attr("x", function(datum, index) { return x(index); }).
		  attr("y", function(datum) { return height - y(datum.s); }).
		  attr("height", function(datum) { return y(datum.s); }).
		  attr("width", barWidth).
		  attr("fill", function(d, i) { return colorlist[i % hizlari_bilagarri_kop]; });
		
		// text on bars
		barDemo.selectAll("text").
		  data(data).
		  enter().
		  append("svg:text").
		  attr("x", function(datum, index) { return x(index) + barWidth; }).
		  attr("y", function(datum) { return height - y(datum.s); }).
		  attr("dx", -barWidth / 2 ).
		  attr("dy", "1em").
		  attr("text-anchor", "middle").
		  filter(function(datum){return datum.s > 0}). 
		  text(function(datum) { return datum.s;}).
		  attr("fill", eztabaida.barrak_testu_kolorea);
		
		var rules = barDemo.append("g");
		
		// Add rules
		rules = rules.selectAll(".rule")
			.data(y.ticks(d3.max(data, function(datum) { return datum.s; })))
			.enter().append("g")
			.attr("class", "rule")
			.attr("transform", function(d) { return "translate(0," + y(d) + ")"; });
	  
		rules.append("line")
			.attr("x2", width);
		
		var xScale;
		var num_ticks;
		
		if (iraupena_min === 1) {
			// Bideoak 0 eta 1 minutu artean irauten badu hau gabe zerbait agertzen da alboetan. Honekin ezer ez.
			xScale = d3.scale.linear().domain([1, (iraupena_min - 1)]).range([width / iraupena_min, width - width / iraupena_min]);
			num_ticks = 0;
			//console.log("< bat");
		} else if (iraupena_min === 2) {
			// Bideoak 1 eta 2 minutu artean irauten badu marra bakarrak agertu behar du.
			// Marra bakarra marrazteko modurik ez dut aurkitu. Bi marra marrazten ditut (0 eta 1) baina 0a ez dago bistan.
			xScale = d3.scale.linear().domain([0, 1]).range([-width, width / 2 - 2]);
			num_ticks = 1;
			//console.log("1 < iraupena < 2");
		} else if (iraupena_min === 3) {
			xScale = d3.scale.linear().domain([1, (iraupena_min - 1)]).rangeRound([width / iraupena_min, width - width / iraupena_min]);
			num_ticks = 1;
			//console.log("2 < iraupena < 3");
		} else if (iraupena_min >= 4 && iraupena_min <= 6) {
			xScale = d3.scale.linear().domain([1, (iraupena_min - 1)]).rangeRound([width / iraupena_min, width - width / iraupena_min]);
			num_ticks = iraupena_min - 1;
			//console.log("4 <= iraupena <= 6");
		} else if (iraupena_min <= denbora_muga) {
			xScale = d3.scale.linear().domain([1, (iraupena_min - 1)]).range([width / iraupena_min, width - width / iraupena_min]);
			num_ticks = iraupena_min;
			//console.log("iraupena <= " + denbora_muga);
		} else {
			xScale = d3.scale.linear().domain([5, iraupena_min_bost - 5]).range([width / (iraupena_min_bost / 5), width - width / (iraupena_min_bost / 5)]);
			num_ticks = iraupena_min_bost / 5;
			//console.log("else");
		}
		
		//console.log("Barra kopurua: " + bars);
		//console.log("num_ticks: " + num_ticks);
		
		var xAxis = d3.svg.axis()
		 .scale(xScale)
		 .orient("bottom")
		 .ticks(num_ticks);
		
		// Add an axis
		barDemo.append("g")
		  .attr("class", "axis")
		  .attr("transform", "translate(0," + (height) + ")")
		  .call(xAxis);
		
		barDemo.selectAll("rect").on("click", function(d,i) {
		});
		
		maxData = d3.max(data, function(datum) { return datum.s; });
	}

	function countWords() {
		for (var i = 0; i < eztabaida.hizlariak.length; i++) {
			speakerWords[eztabaida.hizlariak[i].indizea] = 0;
		}
		
		var results = [];
		var speaking = null;
		
		$('#transcript-content p').each(function(i) {
			var that = this;
			
			for (var i = 0; i < eztabaida.hizlariak.length; i++) {	
				if ($(that).children(':first').text().indexOf(eztabaida.hizlariak[i].aurrizkia) >= 0) {
					speaking = eztabaida.hizlariak[i].indizea;
				}
			}
			
			speakerWords[speaking] = speakerWords[speaking] + $(this).children().length;
		});
		
		//console.dir(speakerWords);
		
		for (var i = eztabaida.hizlariak.length - 1; i >= 0; i--) {
			if (eztabaida.hizlariak[i].bilagarria === 1) {
				results.push(speakerWords[eztabaida.hizlariak[i].indizea]);
				$("#pie-results-kopurua-" + i).text(speakerWords[eztabaida.hizlariak[i].indizea]);
			}
		}
		
		updatePieChart(results);
	}
	
	function cleanWord(w) {
		for (var i = 0; i < eztabaida.hizlariak.length; i++) {
			w = w.replace(eztabaida.hizlariak[i].aurrizkia + " ", "");
		}
    	return w.replace("...","").replace(".","").replace(",","").replace("!","").replace("?","").replace("-","").toLowerCase();
    }
	
	function checkStartParam() {
		var param = getUrlVars()["s"];
		if ( param != null) {  
			var sParam = null;
			if ( param.indexOf('-') >= 0 ) {
				sParam = param.substr(0, param.indexOf('-'));
				var eParam = param.substr(param.indexOf('-')+1,param.length);
				endTime = parseInt(eParam);
				//console.log("endTime = "+endTime);
			} else {
				sParam = param;
				endTime = null;
			}
			
			var s = parseInt(sParam);
			
			//console.log("s = "+sParam);
			
			pop.play(s / 10);
		}
	}
	
	// Bilaketa baten emaitza URLan kodetuta dagoen egiaztatzeko funtzioa.
	function checkKeywordParam() {
		
		// Gako-hitz parametro bat badago (bilaketa baten URLa alegia)
		if (getUrlVars()["k"] != null) {
			
			// Bilatu beharreko hitza(k) eskuratu.
			var s = getUrlVars()["k"];
			
			// Bilatu beharreko hitz bat baino gehiago badaude %20 izango dute hutsuneen ordez. Berriz ere hutsuneak jarriko ditugu.
			s = s.split('%20').join(' ');
			
			// * komodinen ordez %2A dago. Berriz ere asteriskoak jarriko ditugu.
			s = s.split('%2A').join('*');
			
			// Bideoaren metadatuak kargatu arte itxaron behar dugu.
			// Erabiltzaileak bilaketa normal bat egiten duenean ez da beharrezkoa itxarotea, bideoa dagoeneko kargatuta egongo baita.
			// Baina URLan kodetutako bilaketetan (adibidez, ?k=gaur) metadatuak kargatuta daudela ziurtatu behar dugu, bestela pop.duration() NaN baita.
			pop.on('loadedmetadata', function() {
				
				// Bilatu beharreko hitza bilaketaren testu-koadroan jarriko dugu.
				$('#searchStr').val(s);
			
				// Bilaketa abiaraziko dugu klik gertaera deituz.
				$('#search-btn').trigger('click');
			});
		}
	}

	function checkEasterParam() {
		if (getUrlVars()["t"] != null) {    
			var t = getUrlVars()["t"];
			if (t != null) {
				tPause = t;
			}
		}
	}

	function getUrlVars() {
		var vars = [], hash;
		var myWindow = window;
		
		if (parent) {
			myWindow = parent.window;
		}
		
		//if (isAString(locationUrl) == false) {
		if (typeof locationUrl != "string") {
			locationUrl = locationUrl.href;
		}
		
		var hashes = locationUrl.slice(locationUrl.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		
		return vars;
	}
	
	// select text function
	function getSelText() {
		var txt = '';
		if (window.getSelection){
			txt = window.getSelection();
		}
		else if (document.getSelection){
			txt = document.getSelection();
		}
		else if (document.selection){
			txt = document.selection.createRange().text;
		}          
		
		return txt;
	}
	
	function ezkutatu_azpitituluak() {
		
		// Azpitituluak ezkutatu.
		$("#bideoa-azpitituluak").hide();
		
		// Botoiaren testua aldatu azpitituluak OFF daudela adierazteko.
		$("#azpitituluak-botoia").text("Azpitituluak OFF");
	}
	
	function bistaratu_azpitituluak() {
		
		// Azpitituluak bistaratu.
		// Azpitituluen div-a popcorn-ek sortzen du eta ez dut modurik aurkitu id bat gehitzeko,
		// hori dela eta jquery-ko children eta last erabili behar izan ditut.
		$("#bideoa-azpitituluak").show();
		
		// Botoiaren testua aldatu azpitituluak ON daudela adierazteko.
		$("#azpitituluak-botoia").text("Azpitituluak ON");
	}
	
	function prestatu_partekatzeko(e) {
		playSource = true;
		tPause = 0;
		
		var s = 0, e = 0;
 		var select = getSelText(); 
  		var tweetable = select+"";  
		
		var startSpan = select.anchorNode.nextSibling; 
		if (startSpan == null) {
			startSpan = select.anchorNode.parentNode;
		}
		
		var endSpan = select.focusNode.nextSibling;    
		if (endSpan == null) {  
			endSpan = select.focusNode.parentNode.nextElementSibling; 
			if (endSpan == null) {
				endSpan = select.focusNode.parentNode;
			}
		}     
		
		// We can do this better by looking at the complete tweet once generated and then removing from inside the quote until it fits 140 chars 
		if (tweetable.length > 78) {
			tweetable = tweetable.substr(0,75)+'...';
		}
		
		// Short and sweet
		var s = Math.floor(parseInt(startSpan.getAttribute(dataMs)) / 100); 
		var e = Math.floor(parseInt(endSpan.getAttribute(dataMs)) / 100);   
		
		// Make sure s < e
		if (s > e) {
			var temp = e;
			e = s;
			s = temp;
		}
		
		// Check that it isn't a single click ie endtime is not starttime   
		// Also that tweetable is > 0 in length
		if (tweetable.length > 0) {    
			// Clean up window.location in case it already has params on the url    
			var winLoc = locationUrl;      
			var url = winLoc;
			var paramStart = winLoc.indexOf('?');   
			
			if (paramStart > 0) {
				url = winLoc.substr(0,paramStart);
			}
		 
			var theTweet = "'" + tweetable + "' " + url + "?s=" + s + "-" + e + " " + eztabaida.hashTag;//+"&e="+e;  
			
			$('.share-snippet').empty();
			$('.share-snippet').append(theTweet);  
			$('#tweet-like').empty();
			$('#tweet-like').append('<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script><a data-url="" data-text="' + theTweet + '" href="http://twitter.com/share?url=none&count=none" class="twitter-share-button">Tweet</a>');  
			
			var fbLink = "http://www.facebook.com/sharer.php?s=100&p[title]=" + fbTitle + "&p[url]=" + url + "&p[summary]=" + encodeURIComponent(tweetable);
			//var fbLink = "http://www.facebook.com/sharer.php?u="+url+"?s="+s+"-"+e;
			$('#fb-link').attr('href',fbLink);
			$('#fb-link').show();
			
			//http://www.facebook.com/sharer.php?s=100&p[title]=titlehere&p[url]=http://www.yoururlhere.com&p[summary]=yoursummaryhere&p[images][0]=http://www.urltoyourimage.com
		}
	}
	
	/*
	 ***********************************
	 * Interfaze grafikoaren gertaerak *
	 ***********************************
	 */
	
	// Erabiltzaileak bideoaren gaineko Play irudia sakatzen duenean.
	$('.jp-video-play').click(function() {
		// Bideoa martxan jarri.
		// Play irudia erreproduktorearen play gertaeran ezkutatzen dugu.
		pop.play();
		
		return false;
	});
	
	// Erabiltzaileak transkribapeneko hitz bat klikatzen duenean.
	$('#transcript').delegate('span','click',function(e){ 
		playSource = true;
		tPause = 0;
		endTime = null;
		
		// Klikatutako hitza bideoko zein momenturi dagokion kalkulatu.
		var jumpTo = $(this).attr(dataMs) / 1000;
		
		// Dagokion momentuan hasi bideoa erreproduzitzen.
		pop.play(jumpTo);
		
		return false;
	});
	
	// Erabiltzaileak hitz bat bilatzeko testu-koadroa klikatzean.
	$("#searchStr").click(function(e) {
		// Testu lehenetsia bistan badago testu-koadroa garbitu
		if ($("#searchStr").val() == searchDefault) {
			$("#searchStr").val('');
			$('#searchStr').css('color','#000');
		}
	});

	$('.thumb-link').click(function() {
		pop.play($(this).attr('data-start') / 10);
		
	  	endTime = $(this).attr('data-end');
	  	playSource = true;
		tPause = 0;
		
	  	return false;
	});
	
	// Erabiltzaileak hitz bat bilatzeko testu-koadroan zerbait idaztean.
	$("#searchStr").keydown(function(e) {
		// Testu lehenetsia bistan badago testu-koadroa garbitu
		if ($("#searchStr").val() == searchDefault) {
			$("#searchStr").val('');
			$('#searchStr').css('color','#000');
		}
		
		// Enter sakatzen badu dagokion hitzaren bilaketa egin.
    	if(e.which == 13) {
    		playSource = true;
    		tPause = 0;
    		$('#search-btn').trigger('click');
			return false;
    	}
	});
	
	// Erabiltzaileak hitz bat bilatzeko botoia klikatzen duenean.
	$('#search-btn').click(function(e){
		
		// Bilaketan komodina erabili den ala ez adierazten du.
		var komodina = false;
		
		var bideoaren_iraupena_min_goruntz_borobilduta = Math.ceil(pop.duration() / 60);
		var bideoaren_iraupena_ms = pop.duration() * 1000;
		
		// Bideoaren iraupena minututan 5 minutuko tartera goruntz borobilduta.
		var bideoaren_iraupena_min_goruntz_borobilduta_bost = 5 * Math.ceil(bideoaren_iraupena_min_goruntz_borobilduta / 5);
		
		// Zenbat barra bistaratu behar diren.
		if (bideoaren_iraupena_min_goruntz_borobilduta <= denbora_muga) {
			// Adibidez, Urkullu eta Mintegiren bideoan 14 jarriko ditugu (7 minutu x 2 hizlari)
			bars = bideoaren_iraupena_min_goruntz_borobilduta * eztabaida.hizlari_bilagarriak.length;
		} else {
			// Adibidez, 27 minutuko bideo bat -> borobilduta: 30 min -> 30 min / 5 min = 6 barra x n hizlari bilagarri.
			bars = (bideoaren_iraupena_min_goruntz_borobilduta_bost / 5) * eztabaida.hizlari_bilagarriak.length;
		}
		
		// Bideo motzetan (<= denbora_muga min) -> Barra bakoitzak minutu bateko zabalera ordezkatuko du.
		// Bideo luzeen kasuan -> Bost minutuko tarteak erabiliko ditugu.
		var barrako_denbora_tartea;
		
		if (bideoaren_iraupena_min_goruntz_borobilduta <= denbora_muga) {
			barrako_denbora_tartea = 60000;		// 1 minutu.
		} else {
			barrako_denbora_tartea = 300000; 	// 5 minutu.
		}
		
		// Bilaketaren emaitzak barretan erakusteko behar ditugun datuak data arrayan bilduko ditugu.
		// Horretarako behar adina barra dituen array bat sortuko dugu.
		data = new Array(bars);
		
		if (e.originalEvent instanceof MouseEvent) {
			//console.log('cleared');
			playSource = true;
			tPause = 0;
		}

		hitsDetails = [];

		var searchStr = $('#searchStr').val().toLowerCase();

		var matches = [];
		var speakers = [];
		var speakerCount = [];
		var results = []
		
		// Hizlari bilagarrien speakerCount arrayko elementua zerora hasieratuko dugu.
		for (var i = 0; i < eztabaida.hizlariak.length; i++) {
			if (eztabaida.hizlariak[i].bilagarria === 1) {
				speakerCount[i] = 0;
			}
		}
		
		// Transkribapeneko span guztien atzeko planoa berriz ere zuriz margotuko dugu, aurreko bilaketetan nabarmendutakoak kentzeko.
		$('#transcript-content span').css('background-color','white');
		
		// Transkribapeneko hitz guztiak banan bana pasako ditugu.
		$('#transcript-content span').each(function(i) {
			
			// Bilaketaren testu-koadroko edukiak zuriunearen arabera banatuko ditugu.
			var searchWords = searchStr.split(" ");
			
			var matching = false;
			
			// cleanWord funtzioa deitzean lortzen den katea gordetzeko erabiltzen dut.
			var tmp_hitza = "";
			
			if (searchWords[0].slice(-1) === "*") {
				
				// Erabiltzaileak komodina erabili du.
				komodina = true;
				
				// Komodina kendu konparazioetan trabarik egin ez dezan.
				searchWords[0] = searchWords[0].substring(0, searchWords[0].length - 1);
				
			} else {
				
				// Erabiltzaileak ez du komodinik erabili.
				komodina = false;
			}
			
			// Lehen hitza bilaketako lehen hitzarekin bat datorren egiaztatuko dugu.
			// Erabiltzaileak komodina erabili badu.
			if (komodina) {
				
				tmp_hitza = cleanWord($(this).text());
				
				if (tmp_hitza.indexOf(searchWords[0]) === 0) {
					matching = true;
				} else {
					matching = false;
				}
			} else {
				
				// Zehazki bat badator hau egia izango da. cleanWord-ek hitza garbitzen du, behar bezala konparatu ahal izateko.
				if (searchWords[0] == cleanWord($(this).text())) {
					matching = true;
				} else {
					matching = false;
				}
			}
			
			// Momentuz bilaketaren emaitza positiboa bada.
			// Hitz bat baino gehiago bilatu baditu oraindik negatiboa izan daiteke. Begiratu beherago.
			if (matching) {
				
				// Hitz bakarreko bilaketa egin badu erabiltzaileak.
				if (searchWords.length == 1) {
					
					//$(this).css('background-color','yellow');
					
				// Hitz bat baino gehiago dituen bilaketa egin badu erabiltzaileak
				} else {
					
					// Transkribapeneko hurrengo hitza hautatuko dugu.
					var nextWord = $(this).next();
					
					// Bilaketako lehen hitzetik azkenengora arte.
					for (var w=1; w < searchWords.length; w++) {
						
						if (searchWords[w].slice(-1) === "*") {
							
							// Erabiltzaileak komodina erabili du.
							komodina = true;
							
							// Komodina kendu konparazioetan trabarik egin ez dezan.
							searchWords[w] = searchWords[w].substring(0, searchWords[w].length - 1);
							
						} else {
							
							// Erabiltzaileak ez du komodinik erabili.
							komodina = false;
						}
						
						if (komodina) {
							
							tmp_hitza = cleanWord(nextWord.text());
				
							if (tmp_hitza.indexOf(searchWords[w]) !== 0) {
								matching = false;
							}
						} else {
							
							// Momentuko hitza ez badator bilaketako dagokion hitzarekin bat.
							if (searchWords[w] != cleanWord(nextWord.text())) {
								
								// Bilaketaren emaitza negatiboa dela adieraziko dugu.
								matching = false;
							}	
						}
						
						// Transkribapeneko hurrengo hitza hautatuko dugu.
						nextWord = nextWord.next();
					}
				}
				
				// Bilaketaren emaitza positiboa bada.
				if (matching == true) {
					
					// Momentuko hitzaren erreferentzia gorde.
					var thisWord = $(this);
					
					var timeSpan = {};
					timeSpan.s = parseInt($(this).attr(dataMs));
					timeSpan.e = parseInt($(this).attr(dataMs))+parseInt(tPause);
					//console.log('tp='+tPause);
					
					// Hizlaria zein den jakiten saiatuko gara.
					// Horretarako momentuko span-aren gurasoko (<p> bat) lehen elementuaren testua eskuratuko dugu.
					// Hizlari honen txandako lehen <p>-a bada edo bakarra, bertan egongo da hizlariaren aurrizkia.
					var wordElement = $(this).parent().children(':first');
					
					// Hizlariaren aurrizkia + <p>-ko lehen hitza dauzkan <span>-aren testua eskuratu.
					var word = wordElement.text();
					
					// Kontrolerako baldintza. Hizlariaren izenaren bila jarraitu behar dugun bitartean true izango da.
					var condition = true;
					
					while (condition) {
						
						// Hizlari guztiak pasako ditugu, hizlariaren aurrizkia momentuko <p>-aren lehen <span>-ean badago (word aldagaia),
						// hizlariaren izena aurkitu dugu eta bilaketa amaitu da (condition false-k hori esan nahi du).
						for (var i = 0; i < eztabaida.hizlariak.length; i++) {
							if (!(word.indexOf(eztabaida.hizlariak[i].aurrizkia) < 0)) {
								condition = false;
							}
						}
						
						// Bilatzen jarraitu behar badugu.
						if (condition) {
							
							// <p> bat atzerago joan behar dugu, bertako lehen <span>-a aukeratu eta prozesua errepikatu.
							wordElement = wordElement.parent().prev().children(':first');
							word = wordElement.text();
						}
					}
					
					for (var i = 0; i < eztabaida.hizlariak.length; i++) {
						if (eztabaida.hizlariak[i].bilagarria === 1 && word.indexOf(eztabaida.hizlariak[i].aurrizkia) >= 0) {
							speakers.push(eztabaida.hizlariak[i].indizea);
							matches.push($(this).attr(dataMs));
							speakerCount[eztabaida.hizlariak[i].indizea]++;
							for (var w=0; w < searchWords.length; w++) {
								thisWord.css('background-color','yellow');
								thisWord = thisWord.next();
							}	
							theScript.push(timeSpan); 
						}
					}
				}
			}
		});
		
		var hits = new Array(bars);
		
		for (var h=0; h < hits.length; h++) {
			hits[h] = 0;
		}
		
		for (var n=0; n < matches.length; n++) {
			for (var i = 0; i < eztabaida.hizlari_bilagarriak.length; i++) {
				if (speakers[n] == eztabaida.hizlari_bilagarriak[i]) {
					var bar = eztabaida.hizlari_bilagarriak.length * (Math.floor(matches[n] / barrako_denbora_tartea)) + i;
					hits[bar]++;
					if (!hitsDetails[bar]) {
						hitsDetails[bar] = new Array();
					}
					hitsDetails[bar].push(matches[n]);
				}
			}
		}

		for (var h=0; h < hits.length; h++) {
			data[h] = {};
			data[h].s = hits[h];
			//data[h].m = hitsDetails[h];
		}

		//console.log("-------------");
		//console.log(data);
		//console.log(hitsDetails);
		//console.log("-------------");
		
		// The chart gets drawn twice now to fix Opera bug and to make it slide in nicely for other browsers.
		drawBarChart(data); // Moved down to animated callback. Opera bug on 1st chart.
		
		for (var i = eztabaida.hizlariak.length - 1; i >= 0; i--) {
			if (eztabaida.hizlariak[i].bilagarria === 1) {
				results.push(speakerCount[eztabaida.hizlariak[i].indizea]);
				$("#pie-results-kopurua-" + i).text(speakerCount[eztabaida.hizlariak[i].indizea]);
			}
		}
		
		updatePieChart(results);

		$('#pieTitle').text($('#searchStr').val());

		// set up tweet

		var winLoc = locationUrl;      
		var url = winLoc;
		var paramStart = winLoc.indexOf('?');   
		
		if (paramStart > 0) {
			url = winLoc.substr(0,paramStart);
		}
		 
		var keyword = searchStr.split(' ').join('%20');
		
		// Asteriskoak '%2A'rekin ordezkatu.
		keyword = keyword.replace(/\*/g, '%2A');
		
		var theTweet = "Zenbat aldiz aipatu dute '" + searchStr + "'? " + url + "?k=" + keyword + " " + eztabaida.hashTag;//+"&e="+e;  
			 
		$('.share-snippet').empty();
		$('.share-snippet').append(theTweet);  
		$('#tweet-like').empty();
		$('#tweet-like').append('<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script><a data-url="" data-text="'+theTweet+'" href="http://twitter.com/share?url=none&count=none" class="twitter-share-button">Tweet</a>');  

		//var fbLink = "http://www.facebook.com/sharer.php?u="+url+"?k="+keyword;

		var fbLink = "http://www.facebook.com/sharer.php?s=100&p[title]="+fbTitle+"&p[url]="+url+"&p[summary]="+encodeURIComponent(theTweet);

		$('#fb-link').attr('href',fbLink);
		$('#fb-link').show();

		$('.mini-footer').slideUp(function() {
			$('.footer').slideDown(function() {
				if(operaBarChartFix) {
					operaBarChartFix = false;
					drawBarChart(data); // Draw the graph again to keep Opera happy that 1st time.
				}
			});
			$('.body.row').animate({bottom: '164px'}, 500);
			$('#fade-bot').animate({top: '741px'}, 500);
			$('#transcript-inst-panel').fadeOut();
		});

		// uncomment below to activate search term playback
		if (tPause > 0) {	
			//console.log('tPause='+tPause);
			playSource = false;
			end = -1;
			index = 0;
		}
		//console.dir(theScript);

		return false;
	});
	
	$(document).keyup(function(e) {
		
		// Gora, behera, ezker edo eskubi gezietako bat sakatu badu erabiltzaileak.
		if (e.which === 37 || e.which === 38 || e.which === 39 || e.which === 40) {
			
			// Erabiltzaileak hautatutako testua sare sozialetan partekatu ahal izateko prestatu.
			prestatu_partekatzeko(e);	
		}
	});
	
	$('#transcript-content').mouseup(function(e) {     
		
		// Erabiltzaileak hautatutako testua sare sozialetan partekatu ahal izateko prestatu.
		prestatu_partekatzeko(e);
	});
	
	// Erabiltzaileak barretan klikatzen duenean.
	$('#chart').on('click', 'rect, text', function(e){
		playSource = true;
		tPause = 0;
		var top = $('#chart').offset().top;
		var height = $('#chart').height();
		
		// e.pageY: dokumentuaren goiarekiko posizio absolutua. Lehen e.clientY erabiltzen zuen eta hori gurasoarekiko posizio erlatiboa da.
		var piece = (maxData-1) - (Math.floor((e.pageY - top) / (height / maxData)));
		
		//console.log("-------------");
		//console.log("e.pageY: " + e.pageY);
		//console.log("top: " + top);
		//console.log("e.pageY - top: " + (e.pageY - top));
		//console.log("height: " + height);
		//console.log("piece: " + piece);
		//console.log("-------------");
		
		// text items are placed next to rects affecting their indexes so we need to mod
		
		var gIndex = $(this).index() % bars;
		var m = hitsDetails[gIndex][piece];
		jumpTo = m / 1000;
		pop.play(jumpTo);  
		
		var $target = $('#transcript-content span[' + dataMs + '="' + m + '"]').parent(); // The paragraph of the word.
		
		$target = $target.prev().length ? $target.prev() : $target; // Select the previous paragraph if there is one.
		
		// Transcript has progressed beyond last paragraph, select last. Prevents crash in jquery
    	$target = $target.length ? $target : $("#transcript-content span").last().parent();
		
    	$("#transcript-content").stop().scrollTo($target, 1000, {axis:'y', margin:true});
		
		return false;
	});
	
	// Botoi honek azpitituluak bistaratu/ezkutatzen ditu.
	$("#azpitituluak-botoia").click(function() {
		
		// Azpitituluak ON edo OFF daude egiaztatu.
		// Botoiaren testua zein den begiratzea ez da oso egokia baina oraingoz horrelaxe egingo dut.
		if ($(this).text() === "Azpitituluak ON") {
			
			ezkutatu_azpitituluak();
			
		} else {
			
			bistaratu_azpitituluak();
			
		}
	});
	
	$(document).on('click', "a.lowerThirdLink", function() {
		
		// Erabiltzaileak grafismoetako esteka bat klikatzean bideoa pausatu.
		pop.pause();
	});
	
	// Erabiltzaileak infoesteka bat klikatzean.
	$(document).on("click", "#infoesteka", function(event) {
		
		// Hau gabe estekan klikatzean bi fitxa irekitzen ziren, bat esteka delako eta bigarrena div-aren klik gertaeran irekitakoa.
		event.preventDefault();
		
		// Bideoa pausatu.
		pop.pause();
		
		// Dagokion esteka ireki fitxa berri batean.
		window.open($("#infoesteka a").attr("href"), "_blank");
	});
	
	/*
	 **********************************************
	 * Popcorn smart erreproduktorearen gertaerak *
	 **********************************************
	 */
	
	// Erabiltzaileak erreproduktoreko play sakatzen duenean
	pop.on("play", function() {
		// Posterra ezkutatu.
		$("#posterra").hide();
		
		// Play irudia ezkutatu.
		$('.jp-video-play').hide();
		
		if ($("#azpitituluak-botoia").text() === "Azpitituluak OFF") {
			
			ezkutatu_azpitituluak();
			
		}
	})
	
	// Erabiltzaileak erreproduktoreko pause sakatzen duenean
	pop.on("pause", function() {
		// Play irudia bistaratu
		$('.jp-video-play').show();
	})
	
	pop.on("seeking", function() {
		clearTimeout(busySeekId);
		busySeekId = setTimeout(function() {
			$('.jp-video-busy').show();
		},delayBusy);
	})
	
	pop.on("seeked", function() {
		clearTimeout(busySeekId);
		$('.jp-video-busy').hide();
	})
	
	pop.on("waiting", function() {
		clearTimeout(busyWaitId);
		// Hasieran jp-video-busy ez agertzeko iruzkindu dut hau (20140220).
		/*busyWaitId = setTimeout(function() {
			$('.jp-video-busy').show();
		},delayBusy);*/
	})
	
	pop.on("playing", function() {
		clearTimeout(busyWaitId);
		$('.jp-video-busy').hide();
	})
	
	// The prefix variables should always be populated

	var dataMs = "data-ms";

	var demCandPrefix = "IÑIGO URKULLU:";
	var repCandPrefix = "LAURA MINTEGI:";
	var moderatorPrefix = "NARRATZAILEA:";
	
	var fbTitle = eztabaida.fb_izenburua;

	var locationUrl = (window.location != window.parent.location) ? document.referrer: document.location;

	var operaBarChartFix = true;

	var searchDefault = eztabaida.bilaketa_kaxa_testua;

	$('#searchStr').focus();
	$('#searchStr').attr('value',searchDefault);

	$('#main-loader').append('.');

	var maxData = 0;
	var tPause = 0;

	// NB: IE8 D3 support https://github.com/mbostock/d3/issues/619
	// Added Sizzle and es5-shim, but fails silently in IE8.

	// Expose these functions to main scope (Funtzioak atalean daude function baten barruan).
	var initPieChart = function() {},
		updatePieChart = function() {};

	// Closure to keep local vars away from main scope.
	(function() {
		// var pie, arc, arcs, arcLabels, pie_dur, r;
		var w = 120, //width
			h = 120, //height
			r = 60, //radius
			pie_dur = 2000, // 750; // ms
			// color = d3.scale.category20c(), // builtin range of colors
			color = [], // Changed to define an array. Usually this is a function!
			pie = d3.layout.pie().sort(null),
			arc = d3.svg.arc().outerRadius(r), //this will create <path> elements for us using arc data
			arcs, arcLabels;
		
		// Bilagarriak diren hizlarien koloreak gordeko ditugu color arrayan
		for (var i = eztabaida.hizlariak.length - 1; i >= 0; i--) {
			if (eztabaida.hizlariak[i].bilagarria === 1) {
				color.push(eztabaida.hizlariak[i].kolorea);
			}
		};
		
		initPieChart = function() {
			$('#pie-chart').empty();
			
			// var data = [{"label":"-", "value":1},{"label":"-", "value":1}];
			
			var data = [];
			var labels = [];
			
			// Bilagarriak diren hizlarien gazten etiketak gordeko ditugu labels arrayan.
			// data arrayan 1 balioa sartuko dugu hizlari bakoitzeko. Hitzak kontatu aurretik guztiek gazta zati berdina izango baitute.
			for (var i = eztabaida.hizlariak.length - 1; i >= 0; i--) {
				if (eztabaida.hizlariak[i].bilagarria === 1) {
					data.push(1);
					labels.push(eztabaida.hizlariak[i].gazta_etiketa);
				}
			};
			
			var svg = d3.select("#pie-chart")
				.append("svg:svg") //create the SVG element inside the <body>
				// .data([data]) //associate our data with the document
				.attr("width", w) //set the width and height of our visualization (these will be attributes of the <svg> tag
				.attr("height", h);
				// .append("svg:g") //make a group to hold our pie chart
				// .attr("transform", "translate(" + r + "," + r + ")") //move the center of the pie chart from 0, 0 to radius, radius
			
			var arc_grp = svg.append("svg:g")
				.attr("class", "arcGrp")
				.attr("transform", "translate(" + r + "," + r + ")"); //move the center of the pie chart from 0, 0 to radius, radius
			
			var label_grp = svg.append("svg:g")
				.attr("class", "labelGrp")
				.attr("transform", "translate(" + r + "," + r + ")"); //move the center of the pie chart from 0, 0 to radius, radius
			
			// DRAW ARC PATHS
			arcs = arc_grp.selectAll("path")
				.data(pie(data));
			arcs.enter().append("svg:path")
				.attr("stroke", eztabaida.gazta_marra_kolorea)
				// .attr("stroke-width", 0.5)
				.attr("fill", function(d, i) {return color[i];}) // Note using an array and not usual color(i) functions.
				.attr("d", arc)
				.each(function(d) {this._current = d});
			
			// DRAW SLICE LABELS
			arcLabels = label_grp.selectAll("text")
				.data(pie(data));
			arcLabels.enter().append("svg:text")
				.attr("class", "arcLabel")
				.attr("fill", eztabaida.gazta_testu_kolorea)
				.attr("transform", function(d) {
					d.innerRadius = 0;
					d.outerRadius = r;
					return "translate(" + arc.centroid(d) + ")";
				})
				.attr("text-anchor", "middle")
				.text(function(d, i) {return labels[i]; });
		};
		
		// initPieChart();
		
		// Store the currently-displayed angles in this._current.
		// Then, interpolate from this._current to the new angles.
		function arcTween(a) {
			var i = d3.interpolate(this._current, a);
			this._current = i(0);
			return function(t) {
				return arc(i(t));
			};
		}
		
		updatePieChart = function(results) {
			var totCount = 0;
			var data = [];
			var labels = [];
			
			for (var i = 0; i < results.length; i++) {
				totCount = totCount + results[i];
			}
			
			for (var i = 0; i < results.length; i++) {
				if (results[i] == 0) {
					labels[i] = "";
				} else {
					labels[i] = Math.round(results[i] / totCount * 1000) / 10;
					labels[i] += "%";
				}
			}
			
			var allZero = true;
			
			// Avoid zero data
			for (var i = 0; i < results.length; i++) {
				if (results[i]) {
					allZero = false;
				}
			}
			
			if (allZero === true) {
				for (var i = 0; i < results.length; i++) {
					results[i] = 1;
				}
			}

			arcs.data(pie(results)); // recompute angles, rebind data
			arcs.transition().ease("elastic").duration(pie_dur).attrTween("d", arcTween);

			arcLabels.data(pie(results));
			arcLabels.text(function(d, i) {return labels[i]; });
			arcLabels.transition().ease("elastic").duration(pie_dur)
				.attr("transform", function(d) {
					d.innerRadius = 0;
					d.outerRadius = r;
					return "translate(" + arc.centroid(d) + ")";
				})
				.style("fill-opacity", function(d) {return d.value==0 ? 1e-6 : 1;});
		};
	})();
	
	initPieChart();

    var theScript = [];

	var latency = 1000;

	var theScriptState = [];

	var theScriptLength = theScript.length; 
	
	var currentyLoaded = "";
	var currentTime = 0;
	var hints = true;
	var playSource = true;
	
	pop.code({
		start: 0,
		onStart: function (options) {
			//console.log('start')
		},
		onFrame: (function () {
			// Warning: This might start polling before the transcript is loaded and ready.

			var count = 0;
			var endedLoop = false;
		
			return function (options) {
				//console.log('here');
				
				var now = Popcorn.instances[0].media.currentTime*1000;  

				//console.log("now="+now/100);
				//console.log("end="+endTime); 

				if (endTime && endTime < (now / 100) ) {
					pop.pause();
					endTime = null;
				}
				
				var src = "";

				if (endedLoop == true) {
					index = 0;
					endedLoop = false;
					end = -1;
					//console.log("e now="+now);
					//console.log("e end="+end);
					pop.pause();
					//console.log('ended loop');
				}

				if (now > end && playSource == false) {

					//console.log('in');

					//myPlayer.jPlayer("pause"); // MJP: Looks like old code. Commented out.
					index = parseInt(index);

					// check for the end
					if (theScript.length < (index+1) && now > end) {
						pop.pause();

						//console.log("paused");

						// check for loop
						if (getUrlVars()["l"] != null) {
							endedLoop = true;
						} else {
							tPause = 0;
							playSource = true;
						}
					} 
					
					if (theScript.length > index) {  
						// moving to the next block in the target	      
						start = theScript[index].s;   
						end = theScript[index].e;

						//console.log(start);
						//console.log(end);
						//console.log(now);

						//myPlayer.bind($.jPlayer.event.progress + ".fixStart", function(event) {
							//console.log("p now="+now);
							//console.log("p end="+end);
							// Warning: The variable 'start' must not be changed before this handler is called.
							// $(this).unbind(".fixStart"); 
							//console.log('log about to play from '+start);
							pop.play(start / 1000);
							index = index + 1; 
							//end = theScript[index].e;
						//});     

		
						//myPlayer.jPlayer("pause",start);   
					}  
				}   
			}
		})(),
		onEnd: function (options) {
			//console.log('end');
		}
	});
	
	// Hizlari bakoitzaren hitz kopurua biltzen duen arraya.
	var speakerWords = [];
	
	// Hitzen bilaketan erabiltzen den aldagaia.
	var hitsDetails;
	
	var endTime =  null;
	
	var index = "";
	var end = "";
	var start = "";
	
	var p, busySeekId, busyWaitId, delayBusy = 250;
	
	checkEasterParam();
	
	// Hipertranskribapenaren testua bistaratu
	$('#transcript-content').html(eztabaida.hipertranskribapena_testua);
	
	// Hipertranskribapenaren oinarrizko funtzionalitatea hasieratu
	initTranscript(pop);
	
	// Hizlari bakoitzaren hitz kopurua kontatu eta bistaratu
	countWords();
	
	checkStartParam();
	checkKeywordParam();
	
	// Azpitituluen fitxategia parseatu bistaratzeko.
	pop.parseSRT(eztabaida.azpitituluak, {target: "bideoa-azpitituluak"});
	
	for (var i = 0; i < eztabaida.grafismoak.length; i++) {
		//console.log(eztabaida.grafismoak[i]);
		pop.lowerThird({
			"start": eztabaida.grafismoak[i].hasiera,
			"end": eztabaida.grafismoak[i].amaiera,
			"onStart": function() {
				// Azpitituluak gorago agertu behar dute.
				// Zer gertatzen da azpititulurik ez badago?
				if ($("#bideoa-azpitituluak").hasClass("bideoa-azpitituluak-behean")) {
					
					// Azpitituluak behetik gora pasa
					$("#bideoa-azpitituluak").removeClass("bideoa-azpitituluak-behean");
					$("#bideoa-azpitituluak").addClass("bideoa-azpitituluak-goian");
					
					// Azpitituluak hasiera batean behean zeudela adierazten du honek.
					// Lower-third-a ezkutatzean azpitituluak behera pasa behar diren edo ez erabakitzeko erabiltzen dut.
					$("#bideoa-azpitituluak").addClass("bideoa-azpitituluak-behean-zeuden");
				}
			},
			"onEnd": function() {
				// Hasiera batean behean baldin bazeuden, azpitituluek berriz ere behean agertu behar dute.
				if ($("#bideoa-azpitituluak").hasClass("bideoa-azpitituluak-behean-zeuden")) {
					
					// Azpitituluak behera pasa.
					$("#bideoa-azpitituluak").removeClass("bideoa-azpitituluak-goian");
					$("#bideoa-azpitituluak").addClass("bideoa-azpitituluak-behean");
					
					// "bideoa-azpitituluak-behean-zeuden" klasea kendu.
					$("#bideoa-azpitituluak").removeClass("bideoa-azpitituluak-behean-zeuden");
				}
			},
			"target": "jquery_player_1",
			"left": 19.10902696365768,
			"top": 92.5,
			"title": eztabaida.hizlariak[eztabaida.grafismoak[i].indizea_hizlaria].izena,
			"description": eztabaida.hizlariak[eztabaida.grafismoak[i].indizea_hizlaria].grafismoa_deskribapena,
			"transition": "popcorn-fade",
			"logo": eztabaida.hizlariak[eztabaida.grafismoak[i].indizea_hizlaria].grafismoa_logoa,
			"linkUrl": eztabaida.hizlariak[eztabaida.grafismoak[i].indizea_hizlaria].grafismoa_esteka,
			"includeLogo": true,
			"isRTL": false,
			"zindex": 1000,
			"id": "TrackEvent1"
		});	
	}
	
	for (var i = 0; i < eztabaida.infoak.length; i++) {
		//console.log(eztabaida.infoak[i]);
		pop.code({
			start: eztabaida.infoak[i].hasiera,
			end: eztabaida.infoak[i].amaiera,
			onStart: (function(i) {
				return function() {
					$("#infoesteka").append("<img id='infoesteka-irudia' src='" + eztabaida.infoak[i].path_irudia + "'>" +
											"<div id='infoesteka-izenburua'>" + eztabaida.infoak[i].izenburua + "</div>" +
											"<div id='infoesteka-azalpena'>" + eztabaida.infoak[i].azalpena + "</div>" +
											"<hr id='infoesteka-hr'>" +
											"<div id='infoesteka-esteka-div'>" +
												"<a id='infoesteka-esteka' target='_blank' href='" + eztabaida.infoak[i].esteka + "'>" + eztabaida.infoak[i].esteka + "</a>" +
											"</div>");
				}
			})(i),
			onEnd: function() {
				$("#infoesteka").empty();
			}
		});
	}
});
