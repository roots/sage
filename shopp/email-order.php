Content-type: text/html; charset=utf-8
From: <?php shopp('purchase','email-from'); ?>
To: <?php shopp('purchase','email-to'); ?>
Subject: <?php shopp('purchase','email-subject'); ?>

<html>

<div id="header">
<h1><?php bloginfo('name'); ?></h1>
<h2>Project <?php shopp('purchase','id'); ?></h2>
</div>
<div id="body">

<?php shopp('purchase','receipt'); ?>

<?php if (shopp('purchase','notpaid') && shopp('checkout','get-offline-instructions')): ?>
    <p><?php shopp('checkout','offline-instructions'); ?></p>
<?php endif; ?>

</div>

</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;UTF-8" />
  </head>
  <body style="margin: 0px; background-color: #F4F3F4; font-family: Helvetica, Arial, sans-serif; font-size:12px;" text="#444444" bgcolor="#F4F3F4" link="#21759B" alink="#21759B" vlink="#21759B" marginheight="0" topmargin="0" marginwidth="0" leftmargin="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#F4F3F4">
      <tbody>
        <tr>
          <td style="padding: 15px;"><center>
            <table width="550" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
              <tbody>
                <tr>
                  <td align="left">
                    <div style="border: solid 1px #d9d9d9;">
                      <table id="header" style="line-height: 1.6; font-size: 12px; font-family: Helvetica, Arial, sans-serif; border: solid 1px #FFFFFF; color: #444;" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                          <tr>
                            <td style="color: #ffffff;" colspan="2" valign="bottom" height="30">.</td>
                          </tr>
                          <tr>
                            <td style="line-height: 32px; padding-left: 30px;" valign="baseline"><span style="font-size: 32px;"><a style="text-decoration: none;" href="%blog_url%" target="_blank"><img src="http://www.unistrut.com.php53-13.ord1-1.websitetestlink.com/media/unistrut-logo-color-horizontal.png" width="482" height="40" alt="Unistrut"/></a></span></td>
                            <td style="padding-right: 30px;" align="right" valign="baseline"><span style="font-size: 14px; color: #777777;"> </span></td>
                          </tr>
                        </tbody>
                      </table>
                      <table id="content" style="margin-top: 15px; margin-right: 30px; margin-left: 30px; color: #444; line-height: 1.6; font-size: 12px; font-family: Arial, sans-serif;" width="490" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                          <tr>
                            <td style="border-top: solid 1px #d9d9d9;" colspan="2">
                              <div style="padding: 15px 0;">%content%</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table id="footer" style="line-height: 1.5; font-size: 12px; font-family: Arial, sans-serif; margin-right: 30px; margin-left: 30px;" width="490" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                          <tr style="font-size: 11px; color: #999999;">
                            <td style="border-top: solid 1px #d9d9d9;" colspan="2"><img style="padding-top: 28px;" alt="WP" src="http://www.unistrut.com.php53-13.ord1-1.websitetestlink.com/wp-admin/images/wp-logo.png" width="16" height="16" align="right" />
                              <div style="padding-top: 15px; padding-bottom: 1px;"><img style="vertical-align: middle;" alt="Date" src="http://www.unistrut.com.php53-13.ord1-1.websitetestlink.com/wp-admin/images/date-button.gif" width="13" height="13" /> Email sent %date% @ %time%</div>
                              <div><img style="vertical-align: middle;" alt="Contact" src="http://www.unistrut.com.php53-13.ord1-1.websitetestlink.com/wp-admin/images/comment-grey-bubble.png" width="12" height="12" /> For any requests, please contact <a href="mailto:%admin_email%">%admin_email%</a></div>
                            </td>
                          </tr>
                          <tr>
                            <td style="color: #ffffff;" colspan="2" height="15">.</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            </center></td>
        </tr>
      </tbody>
    </table>
  </body>
</html>