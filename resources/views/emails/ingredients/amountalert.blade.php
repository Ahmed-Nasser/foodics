<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>


    <div class="container" style="padding: 1rem; background: #f5f5f5;">
        <p>Greetings from Foodics</p>
        <p>
            We're writing to you to let you know that the <h5>{{$ingredient->name}}</h5> ingredient amount
            reached 50 % OR below in your stock.
            kindly call your providers to provide you with your ingredient share.
            thanks have a good day.
        </p>
    </div>
</html>
