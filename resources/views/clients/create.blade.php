@extends('sections.layout')

@section('title', 'Add New Client')
@section('clients-active', 'active')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Client</h1>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Clients
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.clients.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3 text-primary">Personal Information</h5>
                        
                        <div class="mb-3">
                            <label for="f_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('f_name') is-invalid @enderror" 
                                   id="f_name" name="f_name" value="{{ old('f_name') }}" required>
                            @error('f_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="l_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('l_name') is-invalid @enderror" 
                                   id="l_name" name="l_name" value="{{ old('l_name') }}" required>
                            @error('l_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="b_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('b_date') is-invalid @enderror" 
                                   id="b_date" name="b_date" value="{{ old('b_date') }}" required>
                            @error('b_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cin" class="form-label">CIN (National ID) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cin') is-invalid @enderror" 
                                   id="cin" name="cin" value="{{ old('cin') }}" required>
                            @error('cin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="adrs" class="form-label">Address</label>
                            <textarea class="form-control @error('adrs') is-invalid @enderror" 
                                      id="adrs" name="adrs" rows="3">{{ old('adrs') }}</textarea>
                            @error('adrs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact & License Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3 text-primary">Contact & License Information</h5>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="permis" class="form-label">License Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('permis') is-invalid @enderror" 
                                   id="permis" name="permis" value="{{ old('permis') }}" required>
                            @error('permis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_permis" class="form-label">License Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date_permis') is-invalid @enderror" 
                                   id="date_permis" name="date_permis" value="{{ old('date_permis') }}" required>
                            @error('date_permis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Any additional notes about the client...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        // Auto-calculate age when birth date is selected
        document.getElementById('b_date').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (age < 18) {
                alert('Warning: Client is under 18 years old.');
            }
        });

        // Validate license date
        document.getElementById('date_permis').addEventListener('change', function() {
            const licenseDate = new Date(this.value);
            const today = new Date();
            const years = Math.floor((today - licenseDate) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (years >= 10) {
                alert('Warning: License is ' + years + ' years old. May need renewal.');
            }
        });
    </script>
@endsection