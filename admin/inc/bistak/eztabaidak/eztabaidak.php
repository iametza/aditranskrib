<script type="text/javascript">
	
	function egiaztatu_datuak() {
		
		<?php
		foreach (hizkuntza_idak() as $h_id) {
		?>
		
		if ($("#gehitu-eztabaida-izenburua_<?php echo $h_id; ?>").val() === "") {
			alert("Eztabaidaren izenburua derrigorrezkoa da.");
			
			$("#gehitu-eztabaida-izenburua_<?php echo $h_id; ?>").focus();
			
			return false;
		}
		
		<?php
		}
		?>
		
		if ($("#gehitu-eztabaida-url-bideoa").val() === "") {
			alert("Eztabaidaren bideoaren URLa derrigorrezkoa da.");
			
			$("#gehitu-eztabaida-izenburua-url-bideoa").focus();
			
			return false;
		}
		
		return true;
	}
	
	$(document).ready(function() {
		$(".eztabaida-data").datetimepicker({
			dateFormat: "yy-mm-dd",
			timeFormat: "HH:mm:ss",
			onClose: function(dateText, inst) {
				var id_eztabaida = $("#" + inst.id).attr("data-id-eztabaida");
				console.log("Data: " + dateText);
				console.log("id_eztabaida: " + $("#" + inst.id).attr("data-id-eztabaida"));
				document.location='<?php echo $url_base . $url_param; ?>&aldatu_data_id=' + id_eztabaida + '&data=' + dateText;
			}
		});
	});
</script>

<div class="navbar">
	<div class="navbar-inner">
		<div class="brand">Eztabaidak</div>
		
		<div class="pull-right">
			<a class="btn" href="#gehitu-eztabaida" data-toggle="modal">Gehitu&nbsp;<i class="icon-plus-sign"></i></a>
		</div>
	</div>
</div>

<?php $klassak = array ('', 'class="info"'); ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="100">Data</th>
			<th>Izena</th>
			<th width="100">&nbsp;</th>
			<th width="85">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($elementuak as $elem){
		?>
		<tr <?php echo current ($klassak); ?>>
			<td>
				<input id="eztabaida-data_<?php echo $elem['id']; ?>" data-id-eztabaida="<?php echo $elem['id']; ?>" class="eztabaida-data" type="text" value="<?php echo $elem["data"]; ?>" name="eztabaida-data_<?php echo $elem['id']; ?>">
			</td>
			
			<td class="td_klik"><?php echo elementuaren_testua ("eztabaidak", "izenburua", $elem["id"]); ?></td>
			
			<td>
				<select class="eztabaida-publiko-select" name="publiko_<?php echo $elem["id"]; ?>" onchange="javascript:document.location='<?php echo $url_base . $url_param; ?>&aldatu_egoera_id=<?php echo $elem["id"]; ?>&bal=' + this.options[this.selectedIndex].value;">
					<option value="0"<?php if ($elem["publiko"] == "0") {echo " selected";} ; ?>>pribatua</option>
					<option value="1"<?php if ($elem["publiko"] == "1") {echo " selected";} ; ?>>publikoa</option>
				</select>
			</td>
			
			<td class="td_aukerak">
				<a class="btn" data-toggle="tooltip" title="aldatu" href="<?php echo $url_base . "form" . $url_param; ?>&edit_id=<?php echo $elem["id"]; ?>"><i class="icon-pencil"></i></a>
				<a class="btn" data-toggle="tooltip" title="ezabatu" href="<?php echo $url_base . "form" .  $url_param; ?>&ezab_id=<?php echo $elem["id"]; ?>" onclick="javascript: return (confirm ('Seguru ezabatu nahi duzula?'));"><i class="icon-trash"></i></a>
			</td>
		</tr>
		<?php if (!next ($klassak)) reset ($klassak); } ?>
	</tbody>
</table>

<?php
	// Ponemos los indices de la paginacion en caso de que haya mas de una pagina
	if ($orrikapena["numPags"] > 1) {
		orrikapen_indizeak ($orrikapena, $url_base);
	}
?>

<div id="gehitu-eztabaida" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="gehitu-eztabaida-izenburua-etiketa" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="gehitu-eztabaida-izenburua-etiketa">Gehitu eztabaida</h3>
	</div>
	<form id="gehitu-eztabaida-form" method="post" action="<?php echo $url_base; ?>gehitu_eztabaida" onsubmit="javascript: return egiaztatu_datuak();">
		<div class="modal-body">
			<fieldset>
				<input type="hidden" name="gorde" value="BAI" />
				
				<?php
				foreach (hizkuntza_idak() as $h_id) {
				?>
				
				<fieldset>
					<legend><strong>Izenburua (<?php echo get_dbtable_field_by_id ("hizkuntzak", "izena", $h_id); ?>)</strong></legend>
					
					<div class="control-group">
						<input class="input-xlarge" type="text" id="gehitu-eztabaida-izenburua_<?php echo $h_id; ?>" name="gehitu-eztabaida-izenburua_<?php echo $h_id; ?>" value="" />
					</div>
					
				</fieldset>
				
				<?php
				}
				?>
				
				<div class="control-group">
					<label for="gehitu-eztabaida-url-bideoa">Bideoaren URLa (Youtube edo Vimeo):</label>
					<input class="input-xlarge" type="text" id="gehitu-eztabaida-url-bideoa" name="gehitu-eztabaida-url-bideoa" value="" />
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Itxi</button>
			<button id="gehitu-eztabaida-izenburua-gorde-botoia" class="btn btn-primary" type="submit">Gorde</button>
		</div>
	</form>
</div>