(function($) {
	'use strict';
	$(window).load(function() {
		const table_obj = $('#list-users'),
			table_tbody = table_obj.find('tbody');

		const ResOnSuccess = function(response) {
			table_tbody.html('');
			let output;
			$.each(response.data.users, function(key, value) {
				output += `
                <tr>
                <th ><a href="javascript:void(0);" class="user-detail" data-user-id="${value.id}" >${value.id}</a></th>
                <td ><a href="javascript:void(0);" class="user-detail" data-user-id="${value.id}" >${value.name}</a></td>
                <td ><a href="javascript:void(0);" class="user-detail" data-user-id="${value.id}" >${value.username}</a></td>
                <td ><a href="javascript:void(0);" class="user-detail" data-user-id="${value.id}" >${value.email}</a></td>
                </tr>
                `;
			});
			table_tbody.html(output);

			//attach event
			$('.user-detail').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$('#myModal')
					.find('.modal-body')
					.html('<i class="fa fa-sync"></i>');
				$('#myModal').modal('show');

				const userId = $(this).data('user-id');

				$.ajax({
					type: 'POST',
					url: sfdc_vars.ajaxurl,
					dataType: 'json',
					data: {
						action: 'sfdc_UserDetail',
						sfdc_security: sfdc_vars.ajax_nonce,
						user_id: userId,
					},
					beforeSend() {},
					success: ResUserDetail,
				});
			});
		};

		const ResUserDetail = function(response) {
			$('#myModal')
				.find('.modal-body')
				.html('');

			const { __ } = wp.i18n;

			let user, $output;
			if ((user = response.data.info)) {
				$output = `
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Full Name', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            ${user.name}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Username', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            ${user.username}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Email', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            ${user.email}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Address', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                    <div class="col-md-4">
                        <label>${__('Street', 'sfdc-plugin')}</label>
                    </div>
                    <div class="col-md-8">
                        ${user.address.street}
                    </div>
                            </div>
                            <div class="row">
                    <div class="col-md-4">
                        <label>${__('Suite', 'sfdc-plugin')}</label>
                    </div>
                    <div class="col-md-8">
                        ${user.address.suite}
                    </div>
                            </div>
                            <div class="row">
                    <div class="col-md-4">
                        <label>${__('City', 'sfdc-plugin')}</label>
                    </div>
                    <div class="col-md-8">
                        ${user.address.city}
                    </div>
                            </div>
                            <div class="row">
                    <div class="col-md-4">
                        <label>${__('zipcode', 'sfdc-plugin')}</label>
                    </div>
                    <div class="col-md-8">
                        ${user.address.zipcode}
                    </div>
                            </div>
                            <div class="row">
                    <div class="col-md-4">
                        <label>${__('Geo', 'sfdc-plugin')}</label>
                    </div>
                    <div class="col-md-8">
                    ${__('Lat', 'sfdc-plugin')} : ${
					user.address.geo.lat
				},<br> ${__('Lng', 'sfdc-plugin')} : ${user.address.geo.lng}
                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Phone', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            ${user.phone}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Website', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            ${user.website}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>${__('Company', 'sfdc-plugin')}</label>
                        </div>
                        <div class="col-md-8">
                            ${user.company.name}, ${
					user.company.catchPhrase
				}, ${user.company.bs}
                        </div>
                    </div>
                    `;
			}
			$('#myModal')
				.find('.modal-body')
				.html($output);
		};

		$.ajax({
			type: 'POST',
			url: sfdc_vars.ajaxurl,
			dataType: 'json',
			data: {
				action: 'sfdc_showListUsers',
				sfdc_security: sfdc_vars.ajax_nonce,
			},
			beforeSend() {},
			success: ResOnSuccess,
		});
	});
})(jQuery);
