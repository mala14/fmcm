$(document).ready(function () {
    $('#txtCountry').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "server.php",
			data: 'query=' + query,
                dataType: "json",
                type: "POST",
                success: function (data) {
				result($.map(data, function (item) {
					return item;
                    }));
                }
            });
        }
    });
});
