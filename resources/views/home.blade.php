@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Exchange Rates</div>
                <div class="card-body">
                    <div id="days">
                        <div class='input-group date' id='datetimepicker' style="display: inline-block; width: auto;">
                            <input type='text' class="form-control" style="display:inline; width: auto;" />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <button id="getRatesBtn" class="btn btn-success" type="button" onclick="fetchData()">Fetch</button>
                         <select id="allCurrencies" name="currenciesSelection">
                            <option>Select Currency</option>
                        </select>
                    </div>
                     
                    <div>
                        <table id="rates-table">
                            <thead>
                                <td>Date/Time</td>
                                <td>Currency</td>
                                <td id="date-selected">Rate</td>
                                <td>Rate (NOW)</td>
                                <td>Change</td>
                            </thead>
                            <tbody id="rates-data">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  
<!--<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script src="{{ asset('js/irregular-data-series.js') }}"></script>-->

<script>
    
var period = '0 day';

function init() {
        
    // Fetch all available currencies and build up the currency select
    let currencyOpt = $('#allCurrencies');
    let currenciesSel;
    
    $.ajax({
        type: 'POST',
        url: '/get-available-currencies',
        dataType: 'json',
        data: {'_token': '{{ csrf_token() }}'},
        success: function (result) {
            let allCurrencies = Object.keys(result);
            $(allCurrencies).each(function(indx,elem){
                currenciesSel += '<option value="'+elem+'">'+elem+'</option>';
            });
            
            $(currencyOpt).append(currenciesSel);
            
        }
    });
}
    
function fetchData() {
    
    // We get the saved data from our database for comparison
    let periodSelection = $('#datetimepicker input').val();
    
    if(periodSelection != undefined) {
        period = periodSelection;
    }
    
    console.log(period);
        
    let d = new Date();
    let month = d.getMonth() + 1;
    let day = d.getDate();
    let year = d.getFullYear();
    
    let date = year + '-' + month + '-' + day;
    
    let currency = $('#allCurrencies').val();
    
   
    
    $.ajax({
        type: 'POST',
        url: '/exchange-rates',
        dataType: 'json',
        data: {'_token': '{{ csrf_token() }}', 'currency':currency, 'period':period},
        success: function (result) {
                  
            let table = $('#rates-data');
            
            let html;
            let currenciesSel;
            
            let currenciesData = result.exchange.previous;
            
            let currenciesDataNow = JSON.parse(result.exchange.now);
            
            $('#date-selected').html('Date ');
            $('#date-selected').append(' ('+result.exchange.date+')');
            
            $(table).html('');
            
            
                      
            for (var key in currenciesData) {
                
                let rateChange = ((1-currenciesData[key]/currenciesDataNow[key])*100).toFixed(4);
            
                let positive = false;

                if(rateChange >= 0){
                    positive = true;
                } else {
                    positive = false;
                }
            
                 $(table).append('<tr><td>'+result.exchange.date+'</td><td>'+key +'</td><td>'+currenciesData[key]+'</td><td>'+currenciesDataNow[key]+'</td><td class="'+(positive == true ? 'positive-num':'negative-num')+'">'+rateChange+'</td></tr>');
            }
         
            $(table).append(html);

               // $(table).append('<tr><td>'+$(el).date+'</td></tr>');
         
        }
    });
}
  
$(document).ready(function(){

init();
    
fetchData(period);

$('#datetimepicker').datetimepicker();

// Timed option for dynamic auto refresh table ( TODO )
//setInterval(function(){
//    fetchData(period);
//}, 30000);

});
</script>
@endsection
