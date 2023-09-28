<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datepicker/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}">
</head>

<body>
    <div class="container mt-5">
        <main>
            <div class="row g-5">
                <div class="col-md-12">
                    <h4 class="mb-3">Fill details below</h4>
                    <div id="validation-errors"></div>
                    <form name="company_stock_form" id="company-form">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" class="form-control @error('start_date') is-invalid @enderror"
                                    name="start_date" id="start_date" placeholder="Start Date" readonly>
                                <span class="invalid-feedback error" id="start_date_error"></span>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" class="form-control @error('end_date') is-invalid @enderror"
                                    name="end_date" id="end_date" placeholder="End Date" readonly>
                                <span class="invalid-feedback error" id="end_date_error"></span>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label for="symbol" class="form-label">Company Symbol</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control @error('symbol') is-invalid @enderror"
                                        name="symbol" id="symbol" placeholder="Company symbol">
                                    <div class="invalid-feedback error" id="symbol_error"></div>
                                    @error('symbol')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="email" class="form-label">Email </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="email" placeholder="you@example.com">
                                <span class="invalid-feedback error" id="email_error"></span>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <button class="w-100 btn btn-primary btn-lg mt-3" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row g-5 mt-3 d-none" id="response-html">
                <div class="col-md-6">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Response : </span>
                        <span class="badge bg-primary rounded-pill" id="count-response"></span>
                    </h4>
                    <table id="dataTable" class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Open</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="col-md-6 priceChart">
                    <canvas id="priceChart" width="400" height="200"></canvas>
                </div>

            </div>
        </main>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            
            /* window.onerror = function() {
                location.reload();
            }
             */
            $('#start_date').datepicker({
                format: 'dd-mm-yyyy',
                endDate: '+0d',
                autoclose: true
            });
            $('#end_date').datepicker({
                format: 'dd-mm-yyyy',
                endDate: '+0d',
                autoclose: true
            });

            $.validator.addMethod("endDateCompare", function(value, element, params) {
                var start_date = moment($("#start_date").val(), "DD-MM-YYYY");
                var end_date = moment(value, "DD-MM-YYYY");
                return start_date <= end_date;
            }, "End Date must be greater than or equals to Start Date");

            $.validator.addMethod("startDateCompare", function(value, element, params) {
                var start_date = moment(value, "DD-MM-YYYY");
                var end_date = moment($("#end_date").val(), "DD-MM-YYYY");
                return start_date <= end_date;
            }, "Start Date must be lesser than or equals to End Date");

            jQuery.validator.addMethod("validDate", function(value, element) {
                return this.optional(element) || moment(value, "DD-MM-YYYY").isValid();
            }, "Please enter a valid date in the format DD-MM-YYYY");

            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[A-Z]+$/.test(value);
            }, "Enter a valid symbol (e.g., ABC)");

            $("#company-form").validate({
                focusCleanup: true,
                rules: {
                    symbol: {
                        required: true,
                        lettersonly: true,
                    },
                    start_date: {
                        required: true,
                        validDate: true,
                        max: "{{ date('d-m-Y') }}",
                        startDateCompare: true,
                    },
                    end_date: {
                        required: true,
                        validDate: true,
                        max: "{{ date('d-m-Y') }}",
                        endDateCompare: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                },
                messages: {
                    symbol: {
                        required: "Company Symbol is required",
                        lettersonly: "Enter a valid symbol (e.g., ABC)",
                    },
                    start_date: {
                        required: "Start Date is required",
                        date: "Enter a valid date",
                        max: "Start Date must be less than or equal to current date",
                        startDateCompare: "Start Date must be lesser than or equals to End Date",
                    },
                    end_date: {
                        required: "End Date is required",
                        date: "Enter a valid date",
                        max: "End Date must be less than or equal to current date",
                        endDateCompare: "End Date must be greater than or equals to Start Date",
                    },
                    email: {
                        required: "Email is required",
                        email: "Enter a valid email",
                    },
                },

                errorPlacement: function(error, element) {
                    if (error.text() != '') {
                        $(element).addClass('is-invalid');
                        setTimeout(function() {
                            $(element).removeClass('is-invalid');
                        }, 2000);
                    }
                    $("#" + element.attr("name") + "_error").text(error.text());
                },
                success: function(error) {},
                submitHandler: function(form) {
                    $("#response-html").addClass('d-none');

                    var newTable = $('#dataTable').DataTable();
                    newTable.clear().draw();
                    newTable.destroy();

                    $('#dataTable tbody').html('');

                    $("#validation-errors").removeClass("text text-danger");
                    $("#validation-errors").html("");

                    $('form button[type=submit]').attr('disabled', 'disabled');
                    $('form button[type=submit]').html('Wait..');

                    var formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('getdata') }}",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                var table = $('#dataTable tbody');
                                var prices = response.data;
                                if (prices) {
                                    $("#response-html").removeClass('d-none');

                                    var totalRecords = prices.length;

                                    var currentDate = new Date();
                                    currentDate.setDate(currentDate.getDate() - 30);

                                    var filteredPrices = prices.filter(function(data) {
                                        if(data.hasOwnProperty("date")){
                                            var priceDate = new Date(data.date * 1000);
                                            return priceDate >= currentDate;
                                        }
                                    });
                                    var dates = filteredPrices.map(function(data) {
                                        if(data.hasOwnProperty("date")){
                                            var priceDate = new Date(data.date * 1000);
                                            return priceDate.toLocaleDateString();
                                        }
                                    });

                                    var openPrices = filteredPrices.map(function(data) {
                                        if(data.hasOwnProperty("open")){
                                            return data.open.toFixed(2);
                                        }
                                    });

                                    var closePrices = filteredPrices.map(function(data) {
                                        if(data.hasOwnProperty("close")){
                                            return data.close.toFixed(2);
                                        }
                                    });
                                    
                                    displayChart(dates, closePrices, openPrices);

                                    $('#count-response').text('Total Records: ' +
                                        totalRecords);

                                    $.each(prices, function(index, data) {
                                        if(data.hasOwnProperty("date") && data.hasOwnProperty("open") && data.hasOwnProperty("high") && data.hasOwnProperty("low") && data.hasOwnProperty("close") && data.hasOwnProperty("volume") ){
                                            var date = new Date(data.date * 1000)
                                            .toLocaleString('en-US', {
                                                hour12: true
                                            });
                                            var row = $('<tr>');
                                            row.append($('<td>').text(date));
                                            row.append($('<td>').text(data.open.toFixed(2)));
                                            row.append($('<td>').text(data.high.toFixed(2)));
                                            row.append($('<td>').text(data.low.toFixed(2)));
                                            row.append($('<td>').text(data.close.toFixed(2)));
                                            row.append($('<td>').text(data.volume));
                                            table.append(row);
                                        }
                                    });

                                    $('#dataTable').DataTable();

                                } else {
                                    table.html(
                                        '<tr><td colspan="6">No data available</td></tr>'
                                        );
                                }
                            } else {
                                if (isArray(response.errors)) {
                                    $("#validation-errors").html("<ul>");
                                    for (let i in response.errors) {
                                        $("#validation-errors").append(
                                            "<li class='text text-danger'>" + response
                                            .errors[i] + "</li>");
                                    }
                                    $("#validation-errors").append("</ul>");
                                } else {
                                    $("#validation-errors").addClass("text text-danger");
                                    $("#validation-errors").html(response.errors);
                                }
                            }

                            $('#company-form button[type=submit]').attr('disabled', false);
                            $('#company-form button[type=submit]').html('Submit');
                        },
                        error: function(response) {
                            $("#validation-errors").addClass("text text-danger");
                            $("#validation-errors").html(response);

                            $('#company-form button[type=submit]').attr('disabled', false);
                            $('#company-form button[type=submit]').html('Submit');
                        }
                    });
                    return false;
                }
            });
        });

        function isArray(what) {
            return Object.prototype.toString.call(what) === '[object Array]';
        }

        function displayChart(dates, pricesClose, pricesOpen) {
            let labels = dates.reverse();
            let prices_close = pricesClose.reverse();
            let prices_open = pricesOpen.reverse();
            
            $("canvas#priceChart").remove();
            $("div.priceChart").append('<canvas id="priceChart" width="400" height="200"></canvas>');

            let ctx = document.getElementById('priceChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Open Price",
                        borderColor: 'rgb(9, 39, 232)',
                        data: prices_open,
                        lineTension: 0,
                    },{
                        label: "Close Price",
                        borderColor: 'rgb(255, 238, 0)',
                        data: prices_close,
                        lineTension: 0,
                    }]
                },
                options: {}
            });
        }
    </script>
</body>

</html>
