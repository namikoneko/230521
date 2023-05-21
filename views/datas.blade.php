@extends("layout")

@section("content")

<form class="ins-form" action="./dataInsExe" method="post">

<!--
    <label for="ins-title">title:</label>
    <input type="text" class="inputText form-control" name="title" id="ins-title">

    <br>
-->
    <textarea class="myTextarea form-control data-textarea" name='text'></textarea>
    <input class="btn btn-light mt-2" type='submit' value='insert'>
</form>

<h6 class="mt-2 mb-0">
    内部catId
</h6>


<div class="border-bottom py-2 d-flex">

  @foreach($rows2 as $row)

    <a class="text-decoration-none px-2 ms-2" href='/230521/datas?catId={{$row["catId"]}}'>{{$row["catId"]}}</a>

  @endforeach

</div>


  @foreach($rows as $row)

<div class="border-bottom py-2">
            <span class="">date: {{$row["date"]}}</span>

            <span class="">sort: {{$row["sort"]}}</span>

            <span class="">catId: {{$row["catId"]}}</span>

            <span class="">date2: {{$row["date2"]}}</span>


            <div class="d-inline data-text">{!!$row["text"]!!}</div>


    <div class="d-flex justify-content-between">

        <div class="">

            <a class="d-inline text-decoration-none px-2 py-1 ms-2" href='/230521/dataUp/{{$row["id"]}}'>up</a>

            <a  class="d-inline text-decoration-none px-2 py-1 ms-2" href='/230521/dataUpd/{{$row["id"]}}'>update</a>

        </div>

            <a  class="d-inline text-decoration-none px-2 py-1 ms-2" href='/230521/dataDel/{{$row["id"]}}'>delete</a>
    </div>


</div>

  @endforeach

@endsection
