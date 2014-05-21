<div class="span2">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Atal nagusiak</li>
			<li<?php echo $menu_aktibo == "eztabaidak" ? ' class="active"' : ''?>>
				<a href="<?php echo URL_BASE; ?>eztabaidak">EZTABAIDAK</a>
				<ul class="nav nav-list">
					<li <?php echo $menu_aktibo == "eztabaidak-laguntza" ? ' class="active"' : ''?>>
						<a href="<?php echo URL_BASE; ?>eztabaidak/laguntza">Laguntzaren testua</a>
					</li>
                </ul>
			</li>
		</ul>
	</div>
</div>
