/**
 * 指定期間が予約可能か判定する。
 * DateTime は Luxon の DateTime、calendarData は日付をキーにした Map。
 */
export function canReserve(startDate, endDate, calendarData) {
  if (endDate < startDate) {
    return { result: true, message: '出庫日は入庫日以降の日付を選択してください。' };
  }

  let crossTimeLimit = false;
  for (let date = startDate; date <= endDate; date = date.plus({ days: 1 })) {
    const dateStr = date.toFormat('yyyy-MM-dd');
    const calendar = calendarData.get(dateStr);
    if (!calendar) {
      return { result: false, message: `${dateStr} の空き情報を取得できませんでした。` };
    }

    const limit = calendar.limitData;
    if (date.hasSame(startDate, 'day') && !limit.canCheckIn) {
      return { result: false, message: `${dateStr} は入庫できません。` };
    }
    if (date.hasSame(endDate, 'day')) {
      if (!limit.canCheckOut) {
        return { result: false, message: `${dateStr} は出庫できません。` };
      }
      continue;
    }
    if (!date.hasSame(endDate, 'day') && !limit.canCrossTime) {
        crossTimeLimit = true;
        return { result: false, message: `満車のため、${dateStr} をまたぐ予約はできません。` };
    }
  }

  return { result: true, message: '' };
}
