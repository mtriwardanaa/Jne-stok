<script src="{{ url('assets/js/codebase.core.min.js') }}"></script>
<script src="{{ url('assets/js/codebase.app.min.js') }}"></script>
<script src="{{ url('assets/js/plugins/chartjs/Chart.bundle.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
	window.onload = function() {
		$(".price").each(function() {
			var input = $(this).val();
			var input = input.replace(/[\D\s\._\-]+/g, "");
			input = input ? parseInt( input, 10 ) : 0;
			$(this).val( function() {
				return ( input === 0 ) ? "" : input.toLocaleString( "id" );
			} );
		});
	}

	$('.number').keypress(function(event) {
		if ((event.which != 46 || $(this).val().indexOf('.') == -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

	$('.numberwithdot').keypress(function(event) {
		var string = $(this).val();
		var num = 0;

		if (string != '' && string.indexOf('.') !== -1) {
			num = string.match(/\./g).length;
		}

		if ((event.which != 46 || $(this).val().indexOf('.') == 1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		} else {
			if (event.which == 46) {
				if (num > 0) {
					event.preventDefault();
				}
			}
		}
	});

	$('.numberwithdotmin').keypress(function(event) {
		var string = $(this).val();
		var num = 0;
		var min = 0;

		if (string != '' && string.indexOf('.') !== -1) {
			num = string.match(/\./g).length;
		}

		if (string != '') {
			if (event.which == 45) {
				event.preventDefault();
			}

			if (string.indexOf('-') !== -1) {
				min = string.match(/\-/g).length;
			}
		}

		if ((event.which != 46 || $(this).val().indexOf('.') == 1) && (event.which < 48 || event.which > 57)) {
			if (event.which != 45) {
				event.preventDefault();
			} else {
				if (min > 0) {
					event.preventDefault();
				}
			}
		} else {
			if (event.which == 46) {
				if (num > 0) {
					event.preventDefault();
				}
			}
		}
	});

	$( ".price" ).on( "keyup", numberFormat);
	$( ".price" ).on( "blur", checkFormat);

	function checkFormat(event){
		var data = $( this ).val().replace(/[($)\s\._\-]+/g, '');
		if(!$.isNumeric(data)){
			$( this ).val("");
		}
	}

	function numberFormat(event){
	  // When user select text in the document, also abort.
	  var selection = window.getSelection().toString();
	  if ( selection !== '' ) {
	  	return;
	  }
	  // When the arrow keys are pressed, abort.
	  if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
	  	return;
	  }
	  var $this = $( this );
	  // Get the value.
	  var input = $this.val();
	  var input = input.replace(/[\D\s\._\-]+/g, "");
	  input = input ? parseInt( input, 10 ) : 0;

	  $this.val( function() {
	  	return ( input === 0 ) ? "" : input.toLocaleString( "id" );
	  } );
	}

	$('#formWithPrice').submit(function() {
		$("#submit").attr("disabled", true);

		$( ".price" ).each(function() {
			var number = $( this ).val().replace(/[($)\s\._\-]+/g, '');
			$(this).val(number);
		});
	});
</script>

@yield('script')