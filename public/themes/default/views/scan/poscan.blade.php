<div id="btn-func" class="btn-group" data-toggle="buttons-radio">
  <button type="button" id="new-session" class="action-select btn btn-large active">New</button>
</div>

<div id="btn-func" class="btn-group" data-toggle="buttons-radio">
  <button type="button" class="session-select btn btn-large active" id="45GGVBN" >1</button>
</div>

<div class="form-horizontal">
    <div id="outlet-box">
        {{ Former::select('outlet')->options(Prefs::getOutlet()->OutletToSelection('id','name',false) )->id('scanoutlet') }} &nbsp;<span>select one of existing outlet before scanning</span><br /><br />
    </div>

    {{ Former::text('barcode','')->id('barcode')->class('span9 scan-in')->autocomplete('off')->placeholder('Put cursor in this box before scanning') }}

    <div id="scanResult">

    </div>
</div>

<style type="text/css">
    #btn-func button{
        font-size: 24px;
        font-weight: bold;
        min-width: 150px;
        margin-bottom: 30px;
    }

    #btn-func button.active{
        background-color: #211;
        color: #eee;
    }

    input.scan-in{
        font-size: 26px;
        line-height: 30px;
        height:55px;
    }

</style>

<script type="text/javascript">


$(document).ready(function() {

    $('#barcode').focus();

    $('button.action-select').on('click',function(){
        var action = $(this).html();
        console.log(action);
        if(action == 'Sell'){
            $('#outlet-box').hide();
        }

        if(action == 'Check'){
            $('#outlet-box').show();
        }

        if(action == 'Deliver'){
            $('#outlet-box').show();
            $('[for="scanoutlet"]').html('Deliver To');
        }

        if(action == 'Return'){
            $('#outlet-box').show();
            $('[for="scanoutlet"]').html('Return To');
        }

    });

    $('#barcode').on('keyup',function(ev){
        if(ev.keyCode == '13'){
            onScanResult();
        }
        //$('#barcode').val($('#barcode').val() + event.keyCode);
    });

    $('#barcode').on('focus',function(ev){
        $('#barcode').val('');
        //$('#barcode').val($('#barcode').val() + event.keyCode);
    });

    $('#new-session').on('click',function(){

    });

    function onScanResult(){
        var txtin = $('#barcode').val();
        var outlet_id = $('#scanoutlet').val();
        var session = $('.session-select.active').attr('id');
        var action = $('#btn-func button.active').html();

        console.log(session);

        if( outlet_id == '' || txtin == '' ){
            alert('Select outlet before scanning');
            $('#barcode').focus();
            $('#barcode').val('');
        }else if( typeof session =='undefined' || session == ''){
            alert('No session defined, click on New button to start new session');
        }else{

            var search_outlet = ($('#outlet-active').is(':checked'))?1:0;

            $.post('{{ URL::to('ajax/scan') }}',
                {
                    'txtin':txtin,
                    'outlet_id': outlet_id,
                    'search_outlet': search_outlet,
                    'action':action
                },
                function(data){
                    if(data.result == 'OK'){
                        oTable.fnDraw();
                    }
                    $('#scanResult').html(data.msg);
                    $('#barcode').val('');
                    $('#barcode').focus();
                },'json'
            );
        }

    }

    function clearList(){
        $('#barcode').val('').focus();
    }

});

</script>
