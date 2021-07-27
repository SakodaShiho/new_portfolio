<?php
// ------------------------------
// お問い合わせ確認画面処理
// ------------------------------
// 本番用　
define ( 'INFO_MAIL', 'shiho.s0322@gmail.com'  ) ;

define ( "URL_ROOT", get_url_root ( ) ) ;

// ----------------------------------------
// システムを配置したフォルダのURLの取得
// -------------------------------------
function get_url_root ( )
{
	$str_dir = $_SERVER[ 'REQUEST_URI' ] ;

	if ( "/" != substr ( $str_dir, -1 ) )
	{
		$str_dir = dirname ( $str_dir ) ;
	}

	$str_url_root = "https://" . $_SERVER[ 'SERVER_NAME' ] . $str_dir ;

	return $str_url_root ;
}

$post_w = $_POST ;

foreach ( $post_w as $key=>$value )
{
	$post_w[ $key ] = mb_convert_encoding ( $value, "UTF-8", "auto" ) ;
}

if ( '' == $post_w[ 'name' ] || '' == $post_w[ 'email' ] || '' == $post_w[ 'contents' ] )
{
	header ( "Location: ./index.html" ) ;
	exit ;
}

$str_goto = "" ;

switch ( $post_w[ 'todo' ] )
{
	// 送信実行
	case 'send':

		// メール送信
		send_contact ( $post_w ) ;

		// 送信完了
		$str_goto = goto_page ( ) ;
		break ;

	default :

		foreach ( $post_w as $key=>$value )
		{
			if ( is_array ( $value ) )
			{
				$post[ $key ] = $value ;
				continue ;
			}

			$key = htmlentities ( $key, ENT_QUOTES, "UTF-8" ) ;
			$post[ $key ] = htmlentities ( $value, ENT_QUOTES, "UTF-8" ) ;
			$post[ $key ] = str_replace ( '&apos;', '&#039;', $post[ $key ] ) ;
		}

		// 送信データ生成
		$hide_post = make_hidden_post ( $post ) ;

		break ;
}

// -----------------
// 送信データ生成
// -----------------
function make_hidden_post ( &$post )
{
	$str = "" ;

	foreach ( $post as $key=>$value )
	{
		if ( 0 == strcmp ( $key, 'todo' ) )
		{
			continue ;
		}

		if ( 0 == strcmp ( $key, 'mail_cnf' ) )
		{
			continue ;
		}

		$str .= '<input type="hidden" value="' . $value . '" name="' . $key .
				'">' . "\n"  ;
	}

	return $str ;
}

// ----------------
// メール送信
// ----------------
function send_contact ( $post )
{
	foreach ( $post as $key=>$value )
	{
		$post[ $key ] = str_replace ( '&#039;', '&apos;', $post[ $key ] ) ;
		$post[ $key ] = htmlspecialchars_decode ( $post[ $key ], ENT_QUOTES ) ;
	}

	// 問い合わせ者へのメール送信
	send_inquiry_mail ( $post ) ;

	// 担当部署へのメール送信
	send_admin_mail ( $post ) ;
}

// ---------------------------
// 問い合わせ者へのメール送信
// ---------------------------
function send_inquiry_mail ( $post )
{
	$strSubject = 'SAKODA SHIHO 自動返信' ;
	$strSubject = mb_convert_encoding ( $strSubject, "SJIS", "AUTO" ) ;

	$strBodyHead = $post[ 'name' ] . " 様\n" ;
	$strBodyHead .= "\n" ;
	$strBodyHead .= 'この度は迫田紫穂のポートフォリオサイトよりお問い合わせをいただき、誠にありがとうございます。' . "\n" ;
	$strBodyHead .= 'こちらは、お問い合わせフォームからの自動返信です。' . "\n" ;
	$strBodyHead .= "\n" ;
	$strBodyHead .= 'お問い合わせいただきました内容は、下記の通りです。' . "\n" ;

	$strBodyHead .= "\n" ;
	$strBodyHead = mb_convert_encoding ( $strBodyHead, "SJIS", "AUTO" ) ;

	$strBody = '---------------------------------------------------' . "\n" ;
	$strBody .= "お名前： " . $post[ 'name' ] . "\n" ;
	$strBody .= "メールアドレス： " . $post[ 'email' ] . "\n" ;
	$strBody .= "お問い合わせ内容：\n\n" . $post[ 'contents' ] . "\n" ;
	$strBody .= '--------------------------------------------------' . "\n" ;
	$strBody .= "\n" ;
	$strBody .= '頂戴いたしましたお問い合わせにつきましては、内容を確認の上、' . "\n" ;
	$strBody .= '後日ご連絡させていただきます。' . "\n" ;
	$strBody .= "\n" ;
	$strBody .= "-------------------------------------------------" . "\n" ;
	$strBody .= '迫田 紫穂'. "\n" ;
	$strBody .= "-------------------------------------------------" . "\n" ;

	$strBody = mb_convert_encoding ( $strBody, "SJIS", "AUTO" ) ;

	$strTo = $post[ 'email' ] ;
	$strFrom = "From: " . INFO_MAIL ;

	$strBodyMng = $strBodyHead . $strBody ;

	mb_language("japanese");
	mb_internal_encoding("SJIS");

	// メール送信
	mb_send_mail ( $strTo, $strSubject, $strBodyMng, $strFrom ) ;
}

// ---------------------------
// 会社へのメール送信
// ---------------------------
function send_admin_mail ( $post )
{
	$strSubject = 'ポートフォリオサイトからの問い合わせ ' ;
	$strSubject = mb_convert_encoding ( $strSubject, "SJIS", "AUTO" ) ;

	$strBodyHead = $post[ 'name' ] . " 様よりWEBサイトから以下のお問い合わせがありました。\n" ;
	$strBodyHead .= "\n" ;
	$strBodyHead = mb_convert_encoding ( $strBodyHead, "SJIS", "AUTO" ) ;


	$strBody = '---------------------------------------------------' . "\n" ;
	$strBody .= "お名前： " . $post[ 'name' ] . "\n" ;
	$strBody .= "メールアドレス： " . $post[ 'email' ] . "\n" ;
	$strBody .= "お問い合わせ内容：\n\n" . $post[ 'contents' ] . "\n" ;
	$strBody .= '--------------------------------------------------' . "\n" ;

	$strBody = mb_convert_encoding ( $strBody, "SJIS", "AUTO" ) ;

	$strTo = "shiho.s0322@gmail.com" ;
	$strFrom = "From: " . INFO_MAIL ;

	$strBodyMng = $strBodyHead . $strBody ;

	mb_language("japanese");
	mb_internal_encoding("SJIS");

	// メール送信
	mb_send_mail ( $strTo, $strSubject, $strBodyMng, $strFrom ) ;
}

// -----------------------
// 各ページに振り分け
// -----------------------
function goto_page ( )
{
	$str_goto = "" ;

	// メニューへ
	$str_goto .= '<script type="text/javascript">' . "\n" ;
	$str_goto .= '<!--' . "\n" ;
	$str_goto .= 'location.href = "./thanx.html" ;' . "\n" ;
	$str_goto .= '//-->' . "\n" ;
	$str_goto .= '</script>' . "\n" ;

	return $str_goto ;
}

?>
<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIHO SAKODA │ ポートフォリオサイト</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

<script type="text/javascript">
// -----------
// 送信
// -----------
function chk_send ( )
{
	var form = document.postForm ;
	form.todo.value = "send" ;
	form.action = "confirm.php" ;
	form.submit ( ) ;
}
</script>
</head>

<body>

    <header>
        <div class="logo">
            <h1>SHIHO SAKODA</h1>
        </div>
        <div class="nav_pc">
            <ul>
                <li>PROFILE<br><span>プロフィール</span></li>
                <li>CAREER<br><span>職歴</span></li>
                <li>SKILLS<br><span>スキル</span></li>
                <li>WORKS<br><span>制作実績</span></li>
                <li>CONTACT<br><span>お問い合わせ</span></li>
            </ul>
        </div>
    </header>

    <div class="mv" style="height: 100px;">
    </div>

    <div class="link_area">
        <a href="" target="_blank">
            <div class="link_area_item"><i class="fab fa-twitter"></i></div>
        </a>
        <a href="" target="_blank">
            <div class="link_area_item"><i class="fab fa-github"></i></div>
        </a>
        <a href="" target="_blank">
            <div class="link_area_item"><img src="./img/qiita_favicon_wh.png"></div>
        </a>
        <div class="border"></div>
    </div>


	<main id="contact" class="contact"><?php echo $str_goto ?>
        <section>
            <div class="ttl">
                <h2>CONTACT<span>お問い合わせ</span></h2>
            </div>

			<div class="container">
				<p>内容をご確認の上、送信ボタンを押してください</p>
				<form action="#" method="post" id="postForm" name="postForm" class="CMS-FORM">
					<input type="hidden" value="" name="todo"><?php echo $hide_post ?>
					<div class="confirm-box">
                    <div class="form_area_item">
                        <label for="name">お名前</label>お名前（ご担当者名）</label><?php echo $post[ 'name' ] ?>
					</div>
                    <div class="form_area_item">
                        <label for="email">メールアドレス</label>メールアドレス</label><?php echo $post[ 'email' ] ?>
					</div>
                    <div class="form_area_item">
                        <label for="contents">お問い合わせ内容</label> お問い合わせ内容</label><?php echo $post[ 'contents' ] ?>
					</div>
					</div>
                    <div class="form_area_submit">
                        <input type="submit" value="送信" onClick="chk_send();" class="submit_btn" >
                        <input name="Reset" type="button" value="戻る" class="submit_btn"  onClick="history.back(); return false;" />
                    </div>
				</form>
			</div>
		</article>
	</main>
    <footer>
        <div class=" footer_item">
            <div class="logo">
                <h1>SHIHO SAKODA</h1>
            </div>
            <div class="nav_pc">
                <ul>
                    <li>PROFILE<br><span>プロフィール</span></li>
                    <li>CAREER<br><span>職歴</span></li>
                    <li>SKILLS<br><span>スキル</span></li>
                    <li>WORKS<br><span>制作実績</span></li>
                    <li>CONTACT<br><span>お問い合わせ</span></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2021 SHIHO SAKODA</p>
        </div>
    </footer>

    <script src="./js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="./js/skill.bars.jquery.js"></script>
    <script src="./js/jquery.pinterestGrid.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
</body>

</html>