@include('crud::fields.text')

@if (isset($field['target']) && $field['target'] != null && $field['target'] != '')
    @push('after_scripts')
        <script>
            function rusToLatin(str) {
                var ru = {
                        'а': 'a',
                        'б': 'b',
                        'в': 'v',
                        'г': 'g',
                        'д': 'd',
                        'е': 'e',
                        'ё': 'e',
                        'ж': 'j',
                        'з': 'z',
                        'и': 'i',
                        'к': 'k',
                        'л': 'l',
                        'м': 'm',
                        'н': 'n',
                        'о': 'o',
                        'п': 'p',
                        'р': 'r',
                        'с': 's',
                        'т': 't',
                        'у': 'u',
                        'ф': 'f',
                        'х': 'h',
                        'ц': 'c',
                        'ч': 'ch',
                        'ш': 'sh',
                        'щ': 'shch',
                        'ы': 'y',
                        'э': 'e',
                        'ю': 'u',
                        'я': 'ya',
                        'ъ': 'ie',
                        'ь': '',
                        'й': 'i'
                    },
                    n_str = [];

                for (var i = 0; i < str.length; ++i) {
                    n_str.push(
                        ru[str[i]] ||
                        ru[str[i].toLowerCase()] == undefined && str[i] ||
                        ru[str[i].toLowerCase()].replace(/^(.)/, function(match) {
                            return match.toUpperCase()
                        })
                    );
                }

                return n_str.join('');
            }

            crud.field('{{ $field['target'] }}').onChange(field => {
                let slug = rusToLatin(String(field.value)).toLowerCase().trim()
                    .normalize('NFD') // separate accent from letter
                    .replace(/[\u0300-\u036f]/g, '') // remove all separated accents
                    .replace(/\s+/g, '-') // replace spaces with -
                    .replace(/[^\w\-]+/g, '') // remove all non-word chars
                    .replace(/\-\-+/g, '-') // replace multiple '-' with single '-'

                crud.field('{{ $field['name'] }}').input.value = slug;
            });
        </script>
    @endpush
@endif
