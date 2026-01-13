<x-app-layout>
<div class="container mt-3">

<h4 class="text-white mb-3">
Edit Salary – {{ $salary->employee->first_name }} {{ $salary->employee->last_name }}
</h4>

<form action="{{ route('salary.update',$salary->id) }}" method="POST">
@csrf @method('PUT')

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Allowance</label>
        <input type="number" step="0.01" name="allowance" class="form-control"
               value="{{ $salary->allowance }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Bonus</label>
        <input type="number" step="0.01" name="bonus" class="form-control"
               value="{{ $salary->bonus }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Overtime</label>
        <input type="number" step="0.01" name="overtime" class="form-control"
               value="{{ $salary->overtime }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Advance</label>
        <input type="number" step="0.01" name="advance" class="form-control"
               value="{{ $salary->advance }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Tax</label>
        <input type="number" step="0.01" name="tax" class="form-control"
               value="{{ $salary->tax }}">
    </div>
</div>

<button class="btn btn-success">Update Salary</button>
<a href="{{ route('salary.index') }}" class="btn btn-secondary">Cancel</a>

</form>
</div>
</x-app-layout>
