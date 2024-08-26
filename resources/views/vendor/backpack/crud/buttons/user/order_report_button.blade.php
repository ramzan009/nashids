<div class="col-md-12" style="padding: 0">
    <form method="GET" action="{{ route('order-report') }}">
        <div class="card" style="margin-bottom: 5px;">
            <div class="card-body row">
                <div class="form-group col-md-4">
                    <label>Период от</label>
                    <input type="text" name="from" id="from" placeholder="ДД-ММ-ГГГГ" class="form-control"
                           pattern="^[0-9]{2}-[0-9]{2}-[0-9]{4}$">
                </div>
                <div class="form-group col-md-4">
                    <label>До</label>
                    <input type="text" name="to" placeholder="ДД-ММ-ГГГГ" class="form-control" id="to"
                           pattern="^[0-9]{2}-[0-9]{2}-[0-9]{4}$">
                </div>
                <div class="form-group col-md-4">
                    <label>Почта</label>
                    <input type="email" required name="email" placeholder="example@mail.ru" class="form-control">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="margin-bottom: 24px;">
            <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
            <span data-value="save_and_back">Скачать отчет</span>
        </button>
    </form>
</div>

<script>
    const from = document.getElementById('from');
    const to = document.getElementById('to');

    from.addEventListener('input', mask);
    to.addEventListener('input', mask);

    function mask(e) {
        const currentYear = new Date().getFullYear();

        const parsed = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,2})(\d{0,4})/);

        const day = parsed[1] && parsed[1] > 31 ? 31 : parsed[1];
        const month = parsed[2] && parsed[2] > 12 ? 12 : parsed[2];
        const year = parsed[3] && parsed[3] > currentYear ? currentYear : parsed[3];

        e.target.value = !month ? day : day + '-' + month + (year ? '-' + year : '');
    }
</script>
