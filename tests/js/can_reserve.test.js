import test from 'node:test';
import assert from 'node:assert/strict';
import { canReserve } from '../../public/js/pages/form/can_reserve.js';

/**
 * canReserve が利用する Luxon DateTime の日付操作だけを再現するテスト用クラス。
 * UTCで日付を扱い、実行環境のタイムゾーンによる日付ずれを防ぐ。
 */
class TestDate {
  // ISO形式の日付（YYYY-MM-DD）をテスト対象の日付として保持する。
  constructor(iso) { this.value = new Date(`${iso}T00:00:00Z`); }

  // 指定日数を加えた新しい日付を返す（元の日付は変更しない）。
  plus({ days }) {
    const next = new Date(this.value);
    next.setUTCDate(next.getUTCDate() + days);
    return new TestDate(next.toISOString().slice(0, 10));
  }

  // LuxonのtoFormat('yyyy-MM-dd')と同じ形式で日付文字列を返す。
  toFormat() { return this.value.toISOString().slice(0, 10); }

  // 同じ日かどうかを判定する。canReserveではday単位の比較に使われる。
  hasSame(other, unit) { return unit === 'day' && this.toFormat() === other.toFormat(); }

  // 日付同士の大小比較をできるよう、内部時刻を数値で返す。
  valueOf() { return this.value.valueOf(); }
}

// テストケースを読みやすくするための日付生成関数。
const date = (iso) => new TestDate(iso);

// 指定された制限だけを上書きし、既定では全判定を許可する日のデータを作る。
const dayData = (overrides = {}) => ({
  limitData: { canCheckIn: true, canCheckOut: true, canCrossTime: true, ...overrides },
});

/**
 * startからendまでの日付をMapにしたカレンダーデータを作る。
 * overridesで特定日の制限を変え、missingで特定日のデータを除外できる。
 */
function calendar(start, end, overrides = {}, missing = []) {
  const entries = [];
  for (let cursor = date(start); cursor <= date(end); cursor = cursor.plus({ days: 1 })) {
    const key = cursor.toFormat();
    if (!missing.includes(key)) entries.push([key, dayData(overrides[key] || {})]);
  }
  return new Map(entries);
}

/**
 * canReserveの入力と期待値。
 * 各項目は [テスト名, 入庫日, 出庫日, 日付ごとの制限, 期待結果,
 * 期待メッセージ, カレンダーから除外する日付] の順。
 */
const cases = [
  // 同日予約、設定日をまたがない予約、またぎ不可日の前後関係。
  ['1日予約', '2025-11-10', '2025-11-10', {}, true, ''],
  ['設定日の前日から当日まで', '2025-11-09', '2025-11-10', {}, true, ''],
  ['設定日をまたぐ', '2025-11-09', '2025-11-11', { '2025-11-10': { canCrossTime: false } }, false, '満車のため、2025-11-10 をまたぐ予約はできません。'],
  ['設定日の当日から翌日まで', '2025-11-10', '2025-11-11', { '2025-11-10': { canCrossTime: false } }, false, '満車のため、2025-11-10 をまたぐ予約はできません。'],
  ['設定日の翌日以降', '2025-11-11', '2025-11-12', { '2025-11-10': { canCrossTime: false } }, true, ''],
  ['連続設定日の前に出庫', '2025-10-20', '2025-10-22', { '2025-10-23': { canCrossTime: false }, '2025-10-24': { canCrossTime: false } }, true, ''],
  ['連続設定日をまたぐ', '2025-10-22', '2025-10-25', { '2025-10-23': { canCrossTime: false }, '2025-10-24': { canCrossTime: false } }, false, '満車のため、2025-10-23, 2025-10-24 をまたぐ予約はできません。'],
  ['連続設定の初日から出庫', '2025-10-22', '2025-10-23', { '2025-10-23': { canCrossTime: false }, '2025-10-24': { canCrossTime: false } }, true, ''],
  ['連続設定の初日から最終日まで', '2025-10-23', '2025-10-24', { '2025-10-23': { canCrossTime: false }, '2025-10-24': { canCrossTime: false } }, false, '満車のため、2025-10-23 をまたぐ予約はできません。'],
  ['連続設定期間の終了後', '2025-10-25', '2025-10-27', { '2025-10-23': { canCrossTime: false }, '2025-10-24': { canCrossTime: false } }, true, ''],
  ['離れた設定日の間をまたぐ', '2025-10-23', '2025-10-26', { '2025-10-22': { canCrossTime: false }, '2025-10-25': { canCrossTime: false } }, false, '満車のため、2025-10-25 をまたぐ予約はできません。'],
  ['離れた設定日の前日まで', '2025-10-20', '2025-10-22', { '2025-10-22': { canCrossTime: false }, '2025-10-25': { canCrossTime: false } }, true, ''],
  ['離れた設定日の翌日以降', '2025-10-26', '2025-10-27', { '2025-10-22': { canCrossTime: false }, '2025-10-25': { canCrossTime: false } }, true, ''],
  // 入庫・出庫可否および滞在中のまたぎ可否。
  ['入庫不可', '2025-11-10', '2025-11-10', { '2025-11-10': { canCheckIn: false } }, false, '2025-11-10 は入庫できません。'],
  ['出庫不可', '2025-11-10', '2025-11-10', { '2025-11-10': { canCheckOut: false } }, false, '2025-11-10 は出庫できません。'],
  ['滞在中の日付がまたぎ不可', '2025-11-09', '2025-11-11', { '2025-11-10': { canCrossTime: false } }, false, '満車のため、2025-11-10 をまたぐ予約はできません。'],
  // 入庫日、滞在中、出庫日の各位置でカレンダーデータが欠けている場合。
  ['入庫日データなし', '2025-11-10', '2025-11-11', {}, false, '2025-11-10 の空き情報を取得できませんでした。', ['2025-11-10']],
  ['滞在中データなし', '2025-11-09', '2025-11-11', {}, false, '2025-11-10 の空き情報を取得できませんでした。', ['2025-11-10']],
  ['出庫日データなし', '2025-11-09', '2025-11-10', {}, false, '2025-11-10 の空き情報を取得できませんでした。', ['2025-11-10']],
  // 期間の前後チェックはカレンダーデータ確認より先に行う。
  ['出庫日が入庫日より前', '2025-11-11', '2025-11-10', {}, true, '出庫日は入庫日以降の日付を選択してください。'],
];

// 全ケースで判定結果と利用者向けメッセージの両方を照合する。
for (const [name, start, end, overrides, expectedResult, expectedMessage, missing = []] of cases) {
  test(name, () => {
    // 逆順の日付でもMap生成時は昇順にする。canReserveには元の順序を渡す。
    const data = calendar(start < end ? start : end, start < end ? end : start, overrides, missing);
    const result = canReserve(date(start), date(end), data);
    assert.deepEqual(result, { result: expectedResult, message: expectedMessage });
  });
}
