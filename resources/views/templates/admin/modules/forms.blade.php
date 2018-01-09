<div class="form-wrapper">
    <div id="form-{{ $id }}">
        @if ($form = App\Models\Form::find($id))
            <form role="form" action="/forms/{{ $id }}/send" method="POST" id="form{{ $id }}">
                <input type="hidden" name="form_id" value="{{ $id }}">
                {{ csrf_field() }}

                @if(session()->has('message'))
                    <div data-alert class="alert-box success">
                        {{ session()->get('message') }}
                        <a href="#" class="close">&times;</a>
                    </div>
                @endif

                @if(session()->has('recaptcha'))
                    <div data-alert class="alert-box alert">
                        {{ session()->get('recaptcha') }}
                        <a href="#" class="close">&times;</a>
                    </div>
                @endif

                @foreach ($form->fields()->get() as $start => $field)
                    <div class="row">
                        @if (!$field->hidden_name)
                            <div class="small-3 columns">
                                <label for="{{ $field->formName() }}" class="right inline{{ $errors->has($field->formName()) ? ' error' : '' }}">{{ $field->name }}{{ $field->required ? "*" : "" }}</label>
                            </div>
                        @endif

                        <div class="small-{{ $field->hidden_name ? 12 : 9 }} columns">
                            @if ($field->type == "text" || $field->type == "email" || $field->type == "number")
                                <input id="{{ $field->formName() }}" type="{{ $field->type }}" name="{{ $field->formName() }}" placeholder="{{ $field->placeholder }}{{ $field->required ? "*" : "" }}" value="{{ old($field->formName()) }}">
                            @elseif ($field->type == "textarea")
                                <textarea id="{{ $field->formName() }}" name="{{ $field->formName() }}" placeholder="{{ $field->placeholder }}{{ $field->required ? "*" : "" }}">{{ old($field->formName()) }}</textarea>
                            @elseif ($field->type == "select")
                                <select name="{{ $field->formName() }}">
                                    @forelse($field->options as $option)
                                        <option value="{{$option['name']}}"{{ (old($field->formName()) == $option['name']) ? " selected" : "" }}>
                                            {{ $option['name'] }}
                                        </option>
                                    @empty
                                        <option disabled>Geen optie om te selecteren</option>
                                    @endforelse
                                </select>
                            @elseif ($field->type == "radio")
                                @forelse($field->options as $option)
                                    <input type="radio" name="{{ $field->formName() }}" id="radio-{{ camel_case($option['name']) }}" value="{{$option['name']}}"{{ (old($field->formName()) == $option['name']) ? " checked" : "" }}>
                                    <label for="radio-{{ camel_case($option['name']) }}">{{ $option['name'] }}</label>
                                    <br>
                                @empty
                                    <p>Geen waardes om te selecteren.</p>
                                @endforelse
                            @elseif ($field->type == "checkbox")
                                <input type="checkbox" name="{{ $field->formName() }}" id="checkbox-{{ $field->formName() }}" value="aangevinkt" {{ (old($field->formName()) == "aangevinkt") ? " checked" : "" }}>
                                <label for="checkbox-{{ $field->formName() }}">{{ $field['placeholder'] }}</label>
                                <br>
                            @endif

                            @if ($errors->has($field->formName()))
                                <small class="error">{{ $errors->first($field->formName()) }}</small>
                            @endif
                        </div>
                    </div>

                @endforeach

                <div class="form-group">
                    <div class="form-flex flex-end">
                        @if ($form->recaptcha)
                            <div class="g-recaptcha" data-sitekey="{{ config('skytz.recaptcha_public') }}"></div>
                        @endif
                        <button form="form{{ $id }}" name="submitform" type="submit">Verzenden</button>
                    </div>
                </div>
            </form>
        @else
            <p>Dit formulier bestaat niet meer / is verwijderd.</p>
        @endif
    </div>
</div>