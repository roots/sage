<form method="get" id="searchform" action="{{ $sf_action }}" role="search">
	<label class="screen-reader-text" for="s">{{ $sf_screen_reader_text }}</label>
	<div class="input-group">
		<input class="field form-control" id="s" name="s" type="search"
			placeholder="<?php esc_attr_e( 'Search &hellip;', 'sage' ); ?>" style="height: 2.6rem;">
		<span class="input-group-btn">
			<button class="btn btn-primary m-0 text-white rounded-0" type="submit" style="height: 2.6rem;">
				➤
			</button>
		</span>
	</div>
</form>
