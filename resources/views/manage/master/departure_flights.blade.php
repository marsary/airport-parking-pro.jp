<!-- G-1 到着便マスタ -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">出発便マスタ</li>
      </ul>

      <div class="l-container__inner">
        <form class="p-master-flight__head">
          <div class="l-flex p-master-flight__head-left">
            <dl>
              <dt class="u-mb025">空港</dt>
              <dd class="p-master-flight__head-desc">
                <div class="c-form-select-color">
                  <select name="" id="">
                    <option value="" selected disabled>---</option>
                    <option value="" selected>NRT</option>
                  </select>
                </div>
                <div class="c-form-select-color">
                  <select name="" id="">
                    <option value="" selected disabled>---</option>
                  </select>
                </div>
              </dd>
            </dl>
            <div class="p-master-flight__head-inputArea">
              <input type="text">/<input type="text">/<input type="text">
            </div>
          </div>
          <div class="l-flex p-master-flight__head-right">
            <dl>
              <dt class="u-mb025">到着便</dt>
              <dd class="p-master-flight__head-inputArea">
                <input type="text">-<input type="text">
              </dd>
            </dl>
            <div class="p-master-flight__head-desc">
              <dl>
                <dt class="u-mb025">時</dt>
                <dd>
                  <div class="c-form-select-color">
                    <select name="hours" id="hours">
                      <!-- 時間選択　00から23まで -->
                      <option value="00">00</option>
                      <option value="01">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                    </select>
                  </div>
                </dd>
              </dl>
              <dl>
                <dt class="u-mb025">分</dt>
                <dd>
                  <div class="c-form-select-color">
                    <select name="minutes" id="minutes">
                      <!-- 分選択　00から59まで -->
                      <option value="00">00</option>
                      <option value="01">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                      <option value="25">25</option>
                      <option value="26">26</option>
                      <option value="27">27</option>
                      <option value="28">28</option>
                      <option value="29">29</option>
                      <option value="30">30</option>
                      <option value="31">31</option>
                      <option value="32">32</option>
                      <option value="33">33</option>
                      <option value="34">34</option>
                      <option value="35">35</option>
                      <option value="36">36</option>
                      <option value="37">37</option>
                      <option value="38">38</option>
                      <option value="39">39</option>
                      <option value="40">40</option>
                      <option value="41">41</option>
                      <option value="42">42</option>
                      <option value="43">43</option>
                      <option value="44">44</option>
                      <option value="45">45</option>
                      <option value="46">46</option>
                      <option value="47">47</option>
                      <option value="48">48</option>
                      <option value="49">49</option>
                      <option value="50">50</option>
                      <option value="51">51</option>
                      <option value="52">52</option>
                      <option value="53">53</option>
                      <option value="54">54</option>
                      <option value="55">55</option>
                      <option value="56">56</option>
                      <option value="57">57</option>
                      <option value="58">58</option>
                      <option value="59">59</option>
                    </select>
                  </div>
                </dd>
              </dl>            
            </div>
            <button type='submit' class="u-fw--normal c-button--deep-gray">絞込</button>
          </div>
        </form>

        <div class="p-master-flight__table-container">
          <table class="l-table-list l-table-list--master l-table-list--scroll-vertical">
            <!-- 空港 T 便名 到着日 時刻 出発地 会社名 出発地-->
            <thead class="l-table-list__head l-table-list--scroll-vertical__head">
              <tr>
                <th><div class="c-button-sort">空港</div></th>
                <th><div class="c-button-sort">便名</div></th>
                <th><div class="c-button-sort --asc">到着日</div></th>
                <th><div class="c-button-sort">時刻</div></th>
                <th><div class="c-button-sort">出発地</div></th>
                <th><div class="c-button-sort">会社名</div></th>
                <th><div class="c-button-sort">出発地</div></th>
                <th><div class="c-button-sort">ソート</div></th>
              </tr>
            </thead>
            <tbody class="l-table-list__body">
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
              <tr>
                <td>成田</td>
                <td>T1</td>
                <td>2023/11/15</td>
                <td>09:00</td>
                <td>TPE</td>
                <td>アメリカン航空</td>
                <td>ロサンゼルス</td>
                <td>1</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- 登録ボタン -->
        <div class="l-flex--end">
          <a href="" class="c-link-no-border u-btn-h--m u-mt2 c-button__pagination--next c-button__pagination--next-green">マスタ登録</a>
        </div>
  
      </div><!-- ./l-container__inner --> 

    </main>
  </div>
@endsection
@push("scripts")
  <!-- ソートのスクリプト -->
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>
@endpush