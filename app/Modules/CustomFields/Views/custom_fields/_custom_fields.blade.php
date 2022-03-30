<script type="text/javascript">
    ready(function () {
        autosize(document.querySelectorAll('textarea.custom-form-field'))
    });
</script>
<div class="fw-bold m-3">
    <label class="form-label">@lang('bt.custom_fields')</label>
    <hr>
</div>
@foreach ($customFields as $customField)
    <div class="m-3">
        <label class="fw-bold">{{ $customField->field_label }}</label>
        @if ($customField->field_type == 'dropdown')
            {!! Form::select('custom[' . $customField->column_name . ']', array_combine(array_merge([''], explode(',', $customField->field_meta)), array_merge([''], explode(',', $customField->field_meta))), null, ['class' => 'custom-form-field form-select', 'data-' . $customField->tbl_name . '-field-name' => $customField->column_name]) !!}
        @else
            {!! call_user_func_array('Form::' . $customField->field_type, ['custom[' . $customField->column_name . ']', null, ['class' => 'custom-form-field form-control', 'data-' . $customField->tbl_name . '-field-name' => $customField->column_name]]) !!}
        @endif
    </div>
@endforeach
