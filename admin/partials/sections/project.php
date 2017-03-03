<h3><?php esc_html_e( 'Choose a Project', 'optimizely-x' ); ?></h3>
<input type="hidden" id="project_name" name="project_name" value="<?php echo esc_attr( $project_name ) ?>" />
<select id="project_id" name="project_id">
	<?php $project_id = get_option( 'optimizely_project_id' ); ?>
	<?php if ( ! empty( $project_id ) ) : ?>
		<option value=""><?php esc_html_e( 'Disable Optimizely', 'optimizely-x' ); ?></option>
		<option value="<?php echo esc_attr( $project_id ); ?>" selected><?php echo esc_html( $project_name ); ?></option>
	<?php else : ?>
		<option value=""><?php esc_html_e( 'Choose a project...', 'optimizely-x' ); ?></option>
	<?php endif; ?>
</select>
<p><?php esc_html_e( 'Optimizely will add the following project code to your page automatically:', 'optimizely-x' ); ?></p>
<h3 id="project_code"></h3>
<br/>
