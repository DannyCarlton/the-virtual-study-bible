<div id="module-block-{$name}" class="module-block col-xs-12 col-sm-6 col-md-6 col-lg-3">
	<div class="block icon-block bg-{$type}-faded w-border-2x border-{$type} dark inner-space rounded-2x text-center module"  
		style="text-shadow:1px 1px 10px rgba(0, 0, 0, 0.56)">
		<!-- Progress bar for this module -->
		<div id="progress-{$name}-circle" class="vb-progress mx-auto" data-value='50' 
			style="margin:5px auto;color:#000;display:none;line-height:3.5;background-color:#fff; border-radius:50px;">
			<span class="progress-left">
				<span class="progress-bar border-primary"></span>
			</span>
			<span class="progress-right">
				<span class="progress-bar border-primary"></span>
			</span>
			<div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
				<div id="progress-{$name}-circle-text" class="h3 font-weight-bold">00%</div>
			</div>
		</div>
		<!-- END Progress bar-->
		<i id="module-{$name}-icon" 
			class="fa-solid fa-{$fa_icon} md-icon dp24 box-icon bg-{$type}-faded border-{$type} text-white pill" 
			style="{$icon_style}">
			{$icon_text}
		</i>
		<h6 id="module-{$name}-title" class="box-title poppins-black">{$title}</h6>
		<p id="module-{$name}-text" class="box-description montserrat">{$text}</p>
		<button id="load-{$name}"
			type="button" 
			class="btn btn-load btn-info-faded montserrat"><i class="fa fa-cloud-download"></i>&nbsp;Load Module...</button>
		<button id="loading-{$name}"
			type="button" 
			class="btn btn-loading btn-info-faded montserrat"
			style="	"></button>
		<div id="module-{$name}-installed" class="notice-enable">
			Module installed and enabled!
		</div>
		<button id="disable-{$name}" title="This will disable the module, but leave the data in the database."
			type="button" 
			class="btn btn-disable btn-info-faded montserrat">
			Disable
		</button>
		<div id="module-{$name}-disabled" class="notice-disabled">
			Module installed but disabled.
		</div>
		<button id="enable-{$name}"
			type="button" 
			class="btn btn-enable btn-info-faded montserrat">
			Enable
		</button>
	</div>
</div>
