@extends("layout")

@section("content")

    <form class="ins-form" action='../dataUpdExe' method='post'>
      <input type='hidden' name='id' value={{$row["id"]}}>

      <label for="ins-date2">date2:</label>
      <input type="date" class="inputText form-control" name="date2" id="ins-date2" value={{$row["date2"]}}>

      <label for="ins-sort">sort:</label>
      <input type="text" class="inputText form-control" name="sort" id="ins-sort">

      <label for="ins-catId">catId:</label>
      <input type="text" class="inputText form-control" name="catId" id="ins-catId">

      <textarea class="myTextarea form-control vh-50 mt-3" name='text'>{{$row["text"]}}</textarea>


      <input class="btn btn-light mt-2" type='submit' value='send'>
    </form>


@endsection
