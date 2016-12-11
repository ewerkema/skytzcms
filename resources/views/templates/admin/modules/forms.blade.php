<div class="form-wrapper">
    <div id="form-{{ $id }}">
        @if ($form = App\Models\Form::find($id))
            <form role="form" action="/forms/{{ $id }}/send" method="POST">
                <input type="hidden" name="form_id" value="{{ $id }}">
                {{ csrf_field() }}

                @if(session()->has('message'))
                    <div data-alert class="alert-box success">
                        {{ session()->get('message') }}
                        <a href="#" class="close">&times;</a>
                    </div>
                @endif

                @foreach ($form->fields()->get() as $start => $field)
                    <div class="row">
                        <div class="small-3 columns">
                            <label for="{{ $field->formName() }}" class="right inline{{ $errors->has($field->formName()) ? ' error' : '' }}">{{ $field->name }}{{ $field->required ? "*" : "" }}</label>
                        </div>

                        <div class="small-9 columns">
                            @if ($field->type == "text" || $field->type == "email" || $field->type == "number")
                                <input id="{{ $field->formName() }}" type="{{ $field->type }}" name="{{ $field->formName() }}" placeholder="{{ $field->placeholder }}" value="{{ old($field->formName()) }}" autofocus>
                            @elseif ($field->type == "textarea")
                                <textarea id="{{ $field->formName() }}" name="{{ $field->formName() }}" placeholder="{{ $field->placeholder }}">{{ old($field->formName()) }}</textarea>
                            @elseif ($field->type == "select")
                                <select name="{{ $field->formName() }}">
                                    @forelse($field->options as $option)
                                        <option value="{{$option['value']}}"{{ (old($field->formName()) == $option['value']) ? " selected" : "" }}>
                                            {{ $option['name'] }}
                                        </option>
                                    @empty
                                        <option disabled>Geen optie om te selecteren</option>
                                    @endforelse
                                </select>
                            @elseif ($field->type == "radio")
                                @forelse($field->options as $option)
                                    <input type="radio" name="{{ $field->formName() }}" id="radio-{{ $option['value'] }}" value="{{$option['value']}}"{{ (old($field->formName()) == $option['value']) ? " checked" : "" }}>
                                    <label for="radio-{{ $option['value'] }}">{{ $option['name'] }}</label>
                                @empty
                                    <p>Geen waardes om te selecteren.</p>
                                @endforelse
                            @elseif ($field->type == "checkbox")
                                <input type="checkbox" name="{{ $field->formName() }}" id="checkbox-{{ $field->formName() }}" value="aangevinkt" {{ (old($field->formName()) == "aangevinkt") ? " checked" : "" }}>
                                <label for="checkbox-{{ $field->formName() }}">{{ $field['placeholder'] }}</label>
                            @endif

                            @if ($errors->has($field->formName()))
                                <small class="error">{{ $errors->first($field->formName()) }}</small>
                            @endif
                        </div>
                    </div>

                @endforeach
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="button right" name="submitform">Verzenden</button>
                        <span class="inline right">Velden met een (*) zijn verplicht.</span>
                    </div>
                </div>
            </form>
        @else
            <p>Dit formulier bestaat niet meer / is verwijderd.</p>
        @endif
    </div>
</div>