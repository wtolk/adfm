<div id="{{$id}}" class="row feedbackform">
    @foreach($fields as $field)
        <div class="col col-12 col-md-8 col-md-offset-2 field-feedbackform">
        {!! $field !!}
        </div>
    @endforeach
    <div class="col col-12 col-md-8 col-md-offset-2">
        <button class="btn btn-primary sendfeedback">Отправить </button>
    </div>
</div>

<script>
    $('#{{$id}} .sendfeedback').on('click', function (){
        let fields = $('#{{$id}} .field-feedbackform');
        let data = new Object;
        data['fields'] = new Object;
        data['_token'] = $('meta[name="csrf-token"]').attr('content');
        $.map(fields, function (field, index) {
            data['fields'][$(field).find('.form-control').attr('name')] = $(field).find('.form-control').val();
            // console.log($(field).find('.form-control').val(), $(field).find('.form-control'))
        });
        console.log(data);
        $.ajax({
            type: "POST",
            url: "{{ route('adfm.feedbacks.store') }}",
            data: data
        }).done(function( msg ) {
            alert( "Data Saved: " + msg );
        });
    });


</script>
