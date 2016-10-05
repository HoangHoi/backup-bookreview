<div id="footer" style="margin-top: 2em">
    <div class="container">
        <div class="row">
            <h3 class="footertext">{{ trans('label.footer.about') }}</h3>
            <div class="col-md-6">
                <center>
                    <img src="{{ asset(config('upload.image_member1')) }}" class="img-circle" alt="the-brains">
                    <br>
                    <h4 class="footertext">{{ trans('label.footer.position') }}</h4>
                    <p class="footertext">{{ trans('label.footer.founder1_des') }}<br>
                </center>
            </div>
            <div class="col-md-6">
                <center>
                    <img src="{{ asset(config('upload.image_member2')) }}" class="img-circle" alt="...">
                    <br>
                    <h4 class="footertext">{{ trans('label.footer.position') }}</h4>
                    <p class="footertext">{{ trans('label.footer.founder2_des') }}<br>
                </center>
            </div>
        </div>
        <div class="row">
            <p>
            <center>
                <p class="footertext">{{ trans('label.footer.copyright') }}</p>
            </center>
            </p>
        </div>
    </div>
</div>
