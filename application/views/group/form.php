<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">Group Form</a>
</div>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Group Form</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden">
						<div class="form-group">
							<label for=""> Grup Adı</label>
							<input class="form-control" name="group_name" placeholder="Grup Adı" type="text">
							<div class="validation-message" data-field="group_name"></div>
						</div>
					</form>
					<div class="content-box-footer">
						<button type="button" class="btn btn-primary action" title="cancel" onclick="form_routes('cancel')">Vazgeç</button>
						<button type="button" class="btn btn-primary action" title="save" onclick="form_routes('save')">Kaydet</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	var onLoad = (function() {
		var index = "<?php echo $index; ?>";
		
		if (index != '') {
			datagrid.formLoad('#form-action', index);
		}

		$('.loading-panel').hide();
		$('.form-panel').show();
	})();

	function validate(formData) {
		var returnData;
		$('#form-action').disable([".action"]);
		$("button[title='save']").html("Doğrulanıyor, Lütfen Bekleyiniz...");
		$.ajax({
	        url: "<?php echo base_url() . 'group/validate'; ?>", async: false, type: 'POST', data: formData,
	        success: function(data, textStatus, jqXHR) {
				returnData = data;
	        }
	    });

		$('#form-action').enable([".action"]);
		$("button[title='save']").html("Değişiklikleri Kaydet");
        if (returnData != 'success') {
        	$('#form-action').enable([".action"]);
			$("button[title='save']").html("Değişiklikleri Kaydet");
            $('.validation-message').html('');
            $('.validation-message').each(function() {
                for (var key in returnData) {
                    if ($(this).attr('data-field') == key) {
                        $(this).html(returnData[key]);
                    }
                }
            });
        } else {
		    return 'success';	
        }
	}

	function save(formData) {
		$("button[title='save']").html("Doğrulanıyor, Lütfen Bekleyiniz...");
		$.post("<?php echo base_url() . 'group/action'; ?>", formData).done(function(data) {
        	$('.datagrid-panel').fadeIn();
			$('.form-panel').fadeOut();
			datagrid.reload();
        });
	}

	function cancel() {
		$('.datagrid-panel').fadeIn();
		$('.form-panel').fadeOut();
	}

	function form_routes(action) {
		if (action == 'save') {
			var formData = $('#form-action').serialize();
			if (validate(formData) == 'success') {
				swal({   
					title: "Onaylayın",   
					text: "Kaydetmeyi Onaylıyor musunuz?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					cancelButtonText: "Vazgeç",
					confirmButtonText: "Kaydet",
					closeOnConfirm: true 
				}, function() {
					save(formData);
				});
			}
		} else {
			cancel();
		}
	}

</script>