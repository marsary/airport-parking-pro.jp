<!-- ラベル -->
<th><label for="label1">{{$label->name}}</label></th>
<td>
  <div class="c-form-select-wrap">
    <select name="label_tag[{{$label->id}}]" id="label1">
      <option value="" selected>未選択</option>
      @foreach ($label->tags as $tag)
        <option value="{{$tag->id}}" {{($tag->id == old("label_tag." . $label->id) ) ? 'selected':''}}>{{$tag->name}}</option>
      @endforeach
    </select>
  </div>
</td>
