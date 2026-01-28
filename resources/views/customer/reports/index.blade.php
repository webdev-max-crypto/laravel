<!-- Example for customer report -->
<h1>Submit Report for Warehouse {{ $warehouseId }}</h1>
<form method="POST" action="{{ route('report.store', $warehouseId) }}">
    @csrf
    <textarea name="details" placeholder="Enter report"></textarea>
    <button type="submit">Submit</button>
</form>
