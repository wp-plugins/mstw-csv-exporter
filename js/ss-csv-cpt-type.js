//jQuery(function($) {
jQuery( document ).ready( function(){

	var radios = document.getElementsByName('mstw_csvx_post_type');
	var value;
	for (var i = 0; i < radios.length; i++) {
		console.log( 'radios[' + i + ']= ' + radios[i].value );
		radios[i].onclick = function( ) {
			var curr_type = document.getElementById( 'csvx_type' );
			console.log( 'previous csvx_type= ' + curr_type.value );
			console.log( 'radio button clicked: ' + this.value );
			document.getElementById('csvx_type').value = this.value;
			//document.getElementById('csvx_type')
			console.log( 'new csvx_type= ' + document.getElementById('csvx_type').value );
		};
		
		/*if ( document.getElementById('csvx_type').value === "" ) {
			if ( radios[i].type === 'radio' && radios[i].checked ) {
				// get value, set checked flag or do whatever you need to
				
				document.getElementById('csvx_type').value = radios[i].value;
			
				console.log( 'setting csvx_type= ' + radios[i].value );
			}
		}
		*/
	}
});