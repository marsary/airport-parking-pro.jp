@extends('layouts.member.authenticated')

@section('content')
<div id="common">
<div id="header">
    <input type="button" onclick="self.print();window.close();" value="印刷" style="float: left;" id="print_button">
    <dev id="time_display">読込時刻：<span id="time_display_text"></span></dev>
    <form action="/receipt.php" method="post" style="text-align: right;">
        レジ番号：
        <input type="text" id="regi_id" name="regi_id" value="<?=isset($html['regi_id']) && !empty($html['regi_id']) ? $html['regi_id'] : '';?>">
        <input type="submit" value="再読み込み" id="reload_button">
    </form>
</div>
    <div class="page">
        <section class="ticket">
            <div class="ticket_meta">
                <p>
                    <?= date('Y/m/d H:i', strtotime($html['reciept_time'])); ?>
                </p>
                <p>
                    <?= isset($html['o_name']) && !empty($html['o_name']) ? $html['o_name']  : '　' ?>
                </p>
            </div>
            <!-- ticket_meta -->

            <div class="ticket_number" style="width: 80%;">
                <p>
                    <?= $html['rcp_id'] ?>
                </p>
                <p>
                    <?= $html['user']['used_num'] ?>                
                </p>
            </div>
            <!-- ticket_number -->

            <div class="ticket_detail">
                <div class="ticket_detail_item">
                    <p style="white-space: nowrap; width: 330px !important;" id="user_name">
                            <?= isset($html['name_k']) && !empty($html['name_k']) ? mb_convert_kana($html['name_k'], "Vc"): '　' ?>
                    </p>
                    <p style="width: 103px;"></p>
                    <p style="white-space: nowrap; width: 390px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" id="car_model">
                        <?= $html['car_model'] ?>
                    </p>
                </div>
                <div class="ticket_detail_item">
                    <p>
                        <?= date('Y年m月d日', strtotime($html['load_date'])) ?>
                    </p>
                    <p>
                        <?= sprintf('%04d', $html['car_number']) ?>
                    </p>
                </div>
                <div class="ticket_detail_item">
                    <p>
                        <?= date('Y年m月d日', strtotime($html['unload_date_plan'])) ?>
                    </p>
                    <p> 
                        <?= $html['car_color'] ?>
                    </p>
                </div>
            </div>
            <!-- ticket_detail -->

            <div class="ticket_name">
                <p>
                    <?= $html['stf_name'] ?>
                </p>
            </div>
            <!-- ticket_name -->
        </section>
        <!-- ticket -->

        <section class="receipt">
            <div class="receipt_date">
                <p style="padding-right: 35px;">
                    <?= date('Y', strtotime($html['reciept_time'])); ?>
                </p>
                <p style="padding-right: 35px;">
                    <?= date('m', strtotime($html['reciept_time'])); ?>
                </p>
                <p>
                    <?= date('d', strtotime($html['reciept_time'])); ?>
                </p>
            </div>
            <!-- receipt_date -->

            <div class="receipt_money">
                <p>
                    <?= ($html['total_price'] > 0 ? number_format($html['total_price']) . ' -' : '＊＊＊＊＊＊＊＊'); ?>
                </p>
            </div>
            <!-- receipt_money -->

            <div class="receipt_about">
                <p style="padding-bottom: 8px;">
                    <?= isset($html['o_name']) && !empty($html['o_name']) ? $html['o_name'] : '　' ?>
                </p>
                <p style="padding-bottom: 8px;">
                    <?= isset($html['receipt_addr']) && !empty($html['receipt_addr']) ? $html['receipt_addr'] : '　' ?>
                </p>
                <p style="padding-bottom: 8px;">
                    <?= isset($html['receipt_tel']) && !empty($html['receipt_tel']) ? $html['receipt_tel'] : '　' ?>
                </p>
            </div>
            <!-- receipt_about -->

            <div class="receipt_detail">
                <p>
                    <?= number_format(($html['total_price'] - $html['tax_exemption_price'])); ?>
                </p>
                <p>
                    <?= number_format($html['total_tax']) ?>
                </p>
                <p>
                    <?= number_format($html['tax_exemption_price']) ?>
                </p>
            </div>
            <!-- receipt_detail -->
        </section>
        <!-- receipt -->

        <section class="remarks">
            <div>
                <div><span class="remarks_title">※弊社使用欄※　<small>（お客様より収集した個人情報は駐車場管理に適切に使用いたします。）</small></span></div><div class="remarks_user_num"><?= $html['rcp']['rc_user_num'] ?>人</div>
                <table class="remarks_table">
                    <tbody>
                        <tr>
                            <th>駐車場</th>
                            <td><?= isset($html['o_name']) && !empty($html['o_name']) ? $html['o_name'] : '　' ?></td>
                            <th>顧客ID</th>
                            <td><?= isset($html['user']['u_id']) && !empty($html['user']['u_id']) ? $html['user']['u_id'] : '　' ?></td>
                            <th>氏名</th>
                            <td>
                                <?php
                                    $name = '';
                                    if (isset($html['name']) && !empty($html['name'])) {
                                        $name = $html['name'];
                                    } elseif (isset($html['name_k']) && !empty($html['name_k'])) {
                                        $name = $html['name_k'];
                                    }
                                ?>
                                <?= $name ?>
                            </td>
                        </tr>
                        <tr>
                            <th>受付ID</th>
                            <td><?= $html['rcp_id'] ?><?= !empty($html['user']['used_num']) ? '（' . $html['user']['used_num'] . '回目）' : '' ?>
                            </td>
                            <th>住所</th>
                            <td colspan="3">
                                <?= !empty($html['user']['zip']) ? '〒' . $html['user']['zip'] : ''; ?>
                                <?= $html['user']['addr1'] ?>
                                <?= $html['user']['addr2'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th>携帯</th>
                            <td><?= $html['user']['tel_mb'] ?></td>
                            <th>TEL</th>
                            <td><?= $html['user']['tel'] ?></td>
                            <th>車種</th>
                            <td><?= $html['rcp']['ca_name'] ?> <?= sprintf('%04d', $html['car_number']) ?> <?= $html['car_color'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th>日程</th>
                            <td>
                                <?php if (empty($html['rcp']['rc_one_day_flg'])) : ?>
                                    <?= date('Y.m.d', strtotime($html['load_date'])) ?>-<?= date('Y.m.d', strtotime($html['unload_date_plan'])) ?>(<?= $html['rcp']['days'] ?>日間)
                                <?php else : ?>
                                    <?= date('Y.m.d', strtotime($html['load_date'])) ?>（１日利用）
                                <?php endif ;?>
                            </td>
                            <th>到着便</th>
                            <td colspan="3">
                                <?php
                                    $fit_text = '';
                                    if (!empty($html['rcp']['rc_flt_corp_id']) &&!empty($html['rcp']['rc_flt_id']) && !empty($html['rcp']['unload_time_plan']) && !empty($html['rcp']['fd_name'])) {
                                        if (!empty($html['rcp']['rc_flt_corp_id'])) {
                                            $fit_text .= $html['rcp']['rc_flt_corp_id'];
                                        }
                                        if (!empty($html['rcp']['rc_flt_id'])) {
                                            if (!empty($fit_text)) { $fit_text .= '-'; }
                                            $fit_text .= $html['rcp']['rc_flt_id'];
                                        }
                                        if (!empty($html['rcp']['unload_time_plan'])) {
                                            $fit_text .= '(' . date('H:i', strtotime($html['rcp']['unload_time_plan'])). ')';
                                        } else {
                                            $fit_text .= '(00:00)';
                                        }
                                        if (!empty($html['rcp']['fd_name'])) {
                                            $fit_text .= $html['rcp']['fd_name'];
                                        }
                                    }
                                    echo ($fit_text);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th nowrap>合計金額</th>
                            <td><?= number_format($html['total_price']) ?> 円 
                            </td>
                            <th>割引率</th>
                            <td><?= $html['rcp']['rc_dsc_rate'] . '%' ?>
                            </td>
                            <th>割引券</th>
                            <td>
                                <?= !empty($html['rcp']['rc_dt_price']) ? number_format($html['rcp']['rc_dt_price']) . ' 円' : '' ?>
                                <?= !empty($html['rcp']['dt_name']) ? '（' . $html['rcp']['dt_name'] . '）' : '' ?>
                            </td>
                        </tr>
                        <tr>
                            <th>支払１</th>
                            <td>
                                <?php
                                    $pay1 = '';
                                    if (isset($html['rcp']['pay'][0]) && !empty($html['rcp']['pay'][0])) {
                                        $pay1 .= (isset($html['rcp']['pay'][0]['pay']) && !empty($html['rcp']['pay'][0]['pay']) ? number_format($html['rcp']['pay'][0]['pay']) . ' 円,' : '');
                                        $pay1 .= (isset($html['rcp']['pay'][0]['pt_name']) && !empty($html['rcp']['pay'][0]['pt_name']) ? ' ' . $html['rcp']['pay'][0]['pt_name'] : '');
                                        $pay1 .= (isset($html['rcp']['pay'][0]['pt2_name']) && !empty($html['rcp']['pay'][0]['pt2_name']) ? ' ' . $html['rcp']['pay'][0]['pt2_name'] : '');
                                    }
                                    echo($pay1);
                                ?>
                            </td>
                            <th>支払２</th>
                            <td>
                                <?php
                                    $pay2 = '';
                                    if (isset($html['rcp']['pay'][1]) && !empty($html['rcp']['pay'][1])) {
                                        $pay2 .= (isset($html['rcp']['pay'][1]['pay']) && !empty($html['rcp']['pay'][1]['pay']) ? number_format($html['rcp']['pay'][1]['pay']) . ' 円,' : '');
                                        $pay2 .= (isset($html['rcp']['pay'][1]['pt_name']) && !empty($html['rcp']['pay'][1]['pt_name']) ? ' ' . $html['rcp']['pay'][1]['pt_name'] : '');
                                        $pay2 .= (isset($html['rcp']['pay'][1]['pt2_name']) && !empty($html['rcp']['pay'][1]['pt2_name']) ? ' ' . $html['rcp']['pay'][1]['pt2_name'] : '');
                                    }
                                    echo($pay2);
                                ?>
                            </td>
                            <th>支払３</th>
                            <td>
                                <?php
                                    $pay3 = '';
                                    if (isset($html['rcp']['pay'][2]) && !empty($html['rcp']['pay'][2])) {
                                        $pay3 .= (isset($html['rcp']['pay'][2]['pay']) && !empty($html['rcp']['pay'][2]['pay']) ? number_format($html['rcp']['pay'][2]['pay']) . ' 円,' : '');
                                        $pay3 .= (isset($html['rcp']['pay'][2]['pt_name']) && !empty($html['rcp']['pay'][2]['pt_name']) ? ' ' . $html['rcp']['pay'][2]['pt_name'] : '');
                                        $pay3 .= (isset($html['rcp']['pay'][2]['pt2_name']) && !empty($html['rcp']['pay'][2]['pt2_name']) ? ' ' . $html['rcp']['pay'][2]['pt2_name'] : '');
                                    }
                                    echo($pay3);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>追加pt</th>
                            <td>
                                <?= !empty($html['rcp']['rc_pt_add']) ? $html['rcp']['rc_pt_add'] . ' pt' : ''; ?>
                            </td>
                            <th>使用pt</th>
                            <td>
                                <?= !empty($html['rcp']['rc_pt_used']) ? $html['rcp']['rc_pt_used'] . ' pt' : ''; ?>
                            </td>
                            <th>累積pt</th>
                                <?= !empty($html['rcp']['rc_pt_correct']) ? $html['rcp']['rc_pt_correct'] . ' pt' : ''; ?>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th>代理店</th>
                            <td>
                                <?= sprintf('%04d', $html['rcp']['rc_ag_id1']); ?>-<?= sprintf('%03d', $html['rcp']['rc_ag_id2']); ?>&nbsp;<?= $html['rcp']['ag_name'] ?>
                            </td>
                            <th>マイル</th>
                            <td>
                                <?= $html['rcp']['ml_name'] ?>
                            </td>
                            <th></th>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th>商品</th>
                            <td>
                                <?php
                                    $goods_text = '';
                                    if (!empty($html['rcp']['goods'])) {
                                        foreach ($html['rcp']['goods'] as $goods) {
                                            if (!empty($goods_text)) {
                                                $goods_text .= ',';
                                            }
                                            $goods_text .= $goods['name'];
                                        }
                                    }
                                    echo ($goods_text);
                                ?>
                            </td>
                            <th>取扱</th>
                            <td>
                            <?php
                                    $note_text = '';
                                    if (!empty($html['rcp']['note'])) {
                                        foreach ($html['rcp']['note'] as $note) {
                                            if (!empty($note_text)) {
                                                $note_text .= ',';
                                            }
                                            $note_text .= $note['name'];
                                        }
                                    }
                                    echo ($note_text);
                                ?>
                            </td>
                            <th>レジ</th>
                            <td><?= $html['stf_name'] ?>（レジNo：<?=$html['regi_id']?>）
                            </td>
                        </tr>
                        <tr>
                            <th>　</th>
                            <td>　
                            </td>
                            <th>　</th>
                            <td>　
                            </td>
                            <th>　</th>
                            <td>　
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="bottom_time"><?= date('Y/m/d H:i', strtotime($html['reciept_time'])); ?></p>
            </div>
            <!-- receipt_detail -->
        </section>
        <!-- receipt -->
    </div>
@endsection
@push("scripts")
    <script>
        // 画面表示された際に印刷ボタンにフォーカスする
        $(document).ready( function(){
            // 印刷ボタンにフォーカス
            $('#print_button').focus();
            var now = new Date();
            var Hour = now.getHours();
            var Min = now.getMinutes();
            var Sec = now.getSeconds();

            $("#time_display_text").text(Hour + "時" + Min + "分");

        });
        const userNameText = $('#user_name').text();
        const carModelText = $('#car_model').text();
        const userNameTextElem = document.getElementById("user_name");
        const carModelTextElem = document.getElementById("car_model");

        function selectUserName() {
            userNameTextElem.innerText = userNameText;
            resizeUserText();
        }

        function selectCarModel() {
            carModelTextElem.innerText = carModelText;
            resizeCarText();
        }

        function resizeUserText() {
            /* 文字数が少なくなったときのため、フォントサイズを戻せるようにします。
            他にstyleの属性があればfont-sizeに関するところを除いてstyleに上書きしましょう。
            今回はないのでstyle属性ごと削除します。*/
            var nameSize = 24;
            userNameTextElem.removeAttribute('style');
            console.log(userNameTextElem.getBoundingClientRect().width , userNameTextElem.scrollWidth);
            userNameTextElem.style.whiteSpace ='nowrap';
            userNameTextElem.style.width ='330px';
            userNameTextElem.style.textOverflow = 'ellipsis';
            userNameTextElem.style.overflow = 'hidden';
            for (
            let size = 24;
            userNameTextElem.getBoundingClientRect().width< userNameTextElem.scrollWidth && size >= 15;
            size -= 3
            /* 文字がはみ出すサイズが存在していたので、1ずつ減らすのを3ずつ減らすという少し速いペースでフォントサイズを小さくしてみました。
            こちらには正解不正解はなく、場合によって調整して遊んでみてください。*/
            ) {
                userNameTextElem.style.fontSize = size + "px";
                nameSize = size;
            // userNameTextElem.setAttribute("style", `font-size: ${size}px`); // こちらも可能
            }

            if (nameSize <= 15) {
                userNameTextElem.style.whiteSpace = 'normal';
                userNameTextElem.style.lineHeight = '15px'; 
                userNameTextElem.style.marginTop = 'auto'; 
                userNameTextElem.style.marginBottom = 'auto'; 
                userText =  $('#user_name').text();
                $('#user_name').text(userText.substring(0 ,65) + '...');

            }
        }

        function resizeCarText() {
            /* 文字数が少なくなったときのため、フォントサイズを戻せるようにします。
            他にstyleの属性があればfont-sizeに関するところを除いてstyleに上書きしましょう。
            今回はないのでstyle属性ごと削除します。*/
            carModelTextElem.removeAttribute('style');
            console.log(carModelTextElem.getBoundingClientRect().width , carModelTextElem.scrollWidth);
            carModelTextElem.style.whiteSpace ='nowrap';
            carModelTextElem.style.width ='390px';
            carModelTextElem.style.textOverflow = 'ellipsis';
            carModelTextElem.style.overflow = 'hidden';
            for (
            let size = 24;
            carModelTextElem.getBoundingClientRect().width< carModelTextElem.scrollWidth && size >= 15;
            size -= 3
            /* 文字がはみ出すサイズが存在していたので、1ずつ減らすのを3ずつ減らすという少し速いペースでフォントサイズを小さくしてみました。
            こちらには正解不正解はなく、場合によって調整して遊んでみてください。*/
            ) {
                carModelTextElem.style.fontSize = size + "px";
            // carModelTextElem.setAttribute("style", `font-size: ${size}px`); // こちらも可能
            }
        }
        $(window).focus(function(){ // タブアクティブ
            $('#reload_button').click();
        });
        setTimeout(function () {
            location.reload();
        }, 60000);

        //ブラウザを開いたとき
        selectUserName();
        selectCarModel();
    </script>
@endpush
@push('css')
    <style>
        #print_button, #reload_button {
            width: 150px;
            height: 50px;
        }
        #time_display {
            float: left;
            margin-top: 16px;
            margin-left: 10px;
        }
    </style>
@endpush