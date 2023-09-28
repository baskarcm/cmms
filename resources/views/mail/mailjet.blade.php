
    @include('mail.header')
    <center>
    <div style="background:#ffffff;background-color:#ffffff;margin:-27px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
          <tr>
            <td><p style="font-family:Ubuntu, Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;margin-left: 161px;font-weight:600">Hai! {{$user->firstname}}</p></td>
          </tr>
          <!-- <tr>
            <td style="direction:ltr;font-size:0px;text-align:center;vertical-align:top;">
              <div class="mj-column-per-25 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"> </table>
              </div>
              <div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;word-break:break-word;">
                      <div style="font-size:13px;line-height:1;text-align:center;color:#000000;">
                        <p><span><span><span style="font-family:Ubuntu, Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;font-size: 16px;"></span></span>
                          </span>
                        </p>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="mj-column-per-25 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"> </table>
              </div>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
    <div>
    <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
        <tr>
          <td>
            <div style="font-size:12px;line-height:1;text-align:center;color:#000000;">
                <p><span><span><span style="font-size: 16px;padding: 20px;">{{$event['message']}}</span></span>
                  </span>
                </p>
            </div>
          </td>
        </tr>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:20px;padding-top:20;text-align:center;vertical-align:top;">
              <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;padding-top:10;padding-right:25px;padding-bottom:10px;padding-left:25px;word-break:break-word;">
                      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif, Helvetica, Arial, sans-serif;font-size:13px;line-height:1;text-align:center;color:#000000;">
                        <p>Any questions, comments, concerns?</p>
                        <p>Contact our support staff at <a href="#" style="text-decoration: none; color: inherit;"><span style="font-weight: bold;">fashion@admin.com</span></a></p>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </center>
@include('mail.footer')
    