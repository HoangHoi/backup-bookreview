<html>
    <head>
        <title>{!! trans('general.page_404.title') !!}</title>
    </head>
    <body>
        <h1>{!! trans('general.page_404.not_found') !!}</h1>
        <h4>{!! trans('general.click_here', ['link' => route('home')]) !!}</h4>
    </body>
</html>
