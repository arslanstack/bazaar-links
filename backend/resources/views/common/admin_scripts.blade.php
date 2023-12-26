<script src="{{ asset('admin_assets/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin_assets/js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/datatable/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/popper.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/inspinia.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/pace/pace.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/ladda/spin.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/ladda/ladda.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/plugins/ladda/ladda.jquery.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/jquery.maskedinput.min.js') }}"></script>

<script>
	$(document).ready(function() {
		$('#cancel_btn').click(function(e){
			e.preventDefault();
			var url = $(this).data('url');
			window.location.href =  url;
		});
		$('.cancel_btn').click(function(e){
			e.preventDefault();
			var url = $(this).data('url');
			window.location.href =  url;
		});
		$(".only_number").keypress(function(e){
			var charCode = (e.which) ? e.which : e.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
				return false;
			}else{
				return true;
			}
		});

	});
</script>
@stack('scripts')