@extends('partner.layout-simple')

@section('title', 'Informasi Organisasi')
@section('page-title', 'INFORMASI ORGANISASI')
@section('page-subtitle', '')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-800 border border-green-200 rounded-lg p-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 md:col-span-3">
            <div class="bg-white rounded-xl border p-4">
                <div class="font-semibold text-sm text-gray-700 mb-3">Profil Organisasi</div>
                <ul class="space-y-2 text-sm">
                    <li><a class="text-blue-600" href="#section-info">Informasi Organisasi</a></li>
                    <li class="text-gray-400">Daftar Follower</li>
                    <li class="text-gray-400">Analitik Profil</li>
                    <li class="text-gray-400">Keanggotaan</li>
                </ul>
            </div>
        </div>
        <div class="col-span-12 md:col-span-9">
            <div id="section-info" class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Informasi Organisasi</h3>

                <form method="POST" action="{{ route('diantaranexus.organization.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Logo Organisasi</label>
                        <div class="flex items-center space-x-4">
                            <img id="logo-preview" class="h-16 w-16 rounded-full object-cover bg-gray-100" src="{{ $organization->logo ? asset($organization->logo) : 'https://via.placeholder.com/64' }}" alt="Logo">
                            <input id="logo-input" type="file" name="logo" accept="image/*" class="text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Nama Organisasi</label>
                        <input name="name" value="{{ old('name', $organization->name) }}" class="w-full border rounded-lg px-3 py-2" required />
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('description', $organization->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Bidang Usaha</label>
                            <input name="type" value="{{ old('type', $organization->type) }}" class="w-full border rounded-lg px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Website</label>
                            <input name="website" value="{{ old('website', $organization->website) }}" class="w-full border rounded-lg px-3 py-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Alamat</label>
                            <input name="address" value="{{ old('address', data_get($organization->contact_info, 'address')) }}" class="w-full border rounded-lg px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Nomor Telepon</label>
                            <input name="phone" value="{{ old('phone', data_get($organization->contact_info, 'phone')) }}" class="w-full border rounded-lg px-3 py-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Email</label>
                            <input name="email" value="{{ old('email', data_get($organization->contact_info, 'email')) }}" class="w-full border rounded-lg px-3 py-2" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Social Media</label>
                        @php $socials = old('socials', data_get($organization->contact_info, 'socials', [])); @endphp
                        <div id="socials-list" class="space-y-2">
                            @forelse($socials as $i => $s)
                                <div class="flex gap-2">
                                    <input name="socials[{{ $i }}][platform]" value="{{ $s['platform'] ?? '' }}" placeholder="Platform (Instagram)" class="flex-1 border rounded-lg px-3 py-2" />
                                    <input name="socials[{{ $i }}][handle]" value="{{ $s['handle'] ?? '' }}" placeholder="@username / url" class="flex-1 border rounded-lg px-3 py-2" />
                                </div>
                            @empty
                                <div class="flex gap-2">
                                    <input name="socials[0][platform]" placeholder="Platform (Instagram)" class="flex-1 border rounded-lg px-3 py-2" />
                                    <input name="socials[0][handle]" placeholder="@username / url" class="flex-1 border rounded-lg px-3 py-2" />
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-social" class="mt-2 text-sm text-blue-600">+ Tambah Akun</button>
                    </div>

                    <!-- Signatures -->
                    <div class="pt-2">
                        <h4 class="font-semibold text-gray-900 mb-2">Tanda Tangan Penyelenggara</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Signature 1 -->
                            <div class="border rounded-lg p-4">
                                <div class="mb-3 font-medium text-sm text-gray-700">Tanda Tangan #1</div>
                                <div class="grid grid-cols-1 gap-3 mb-3">
                                    <input name="signature1_name" value="{{ old('signature1_name', $organization->signature1_name) }}" placeholder="Nama" class="w-full border rounded-lg px-3 py-2" />
                                    <input name="signature1_title" value="{{ old('signature1_title', $organization->signature1_title) }}" placeholder="Jabatan" class="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div class="flex items-center space-x-4 mb-3">
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="radio" name="signature1_type" value="upload" {{ old('signature1_type', $organization->signature1_type)==='upload' ? 'checked' : '' }}>
                                        <span>Upload</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="radio" name="signature1_type" value="draw" {{ old('signature1_type', $organization->signature1_type)==='draw' ? 'checked' : '' }}>
                                        <span>Gambar</span>
                                    </label>
                                </div>
                                <div class="space-y-3">
                                    <div id="sig1-upload" class="{{ old('signature1_type', $organization->signature1_type)==='draw' ? 'hidden' : '' }}">
                                        <div class="flex items-center space-x-4">
                                            <img id="sig1-preview" class="h-12 object-contain" src="{{ $organization->signature1_image ? asset($organization->signature1_image) : 'https://via.placeholder.com/120x48?text=Preview' }}" alt="Sig1">
                                            <input type="file" name="signature1_image" accept="image/*" class="text-sm" />
                                        </div>
                                    </div>
                                    <div id="sig1-draw" class="{{ old('signature1_type', $organization->signature1_type)==='draw' ? '' : 'hidden' }}">
                                        <canvas id="sig1-canvas" class="border rounded w-full bg-white" height="120"></canvas>
                                        <div class="flex gap-2 mt-2 text-sm">
                                            <button type="button" class="px-3 py-1 border rounded" onclick="clearCanvas('sig1-canvas')">Clear</button>
                                            <button type="button" class="px-3 py-1 border rounded" onclick="applySignature('sig1')">Gunakan</button>
                                        </div>
                                        <input type="hidden" name="signature1_draw" id="signature1_draw" />
                                    </div>
                                </div>
                            </div>

                            <!-- Signature 2 -->
                            <div class="border rounded-lg p-4">
                                <div class="mb-3 font-medium text-sm text-gray-700">Tanda Tangan #2</div>
                                <div class="grid grid-cols-1 gap-3 mb-3">
                                    <input name="signature2_name" value="{{ old('signature2_name', $organization->signature2_name) }}" placeholder="Nama" class="w-full border rounded-lg px-3 py-2" />
                                    <input name="signature2_title" value="{{ old('signature2_title', $organization->signature2_title) }}" placeholder="Jabatan" class="w-full border rounded-lg px-3 py-2" />
                                </div>
                                <div class="flex items-center space-x-4 mb-3">
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="radio" name="signature2_type" value="upload" {{ old('signature2_type', $organization->signature2_type)==='upload' ? 'checked' : '' }}>
                                        <span>Upload</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="radio" name="signature2_type" value="draw" {{ old('signature2_type', $organization->signature2_type)==='draw' ? 'checked' : '' }}>
                                        <span>Gambar</span>
                                    </label>
                                </div>
                                <div class="space-y-3">
                                    <div id="sig2-upload" class="{{ old('signature2_type', $organization->signature2_type)==='draw' ? 'hidden' : '' }}">
                                        <div class="flex items-center space-x-4">
                                            <img id="sig2-preview" class="h-12 object-contain" src="{{ $organization->signature2_image ? asset($organization->signature2_image) : 'https://via.placeholder.com/120x48?text=Preview' }}" alt="Sig2">
                                            <input type="file" name="signature2_image" accept="image/*" class="text-sm" />
                                        </div>
                                    </div>
                                    <div id="sig2-draw" class="{{ old('signature2_type', $organization->signature2_type)==='draw' ? '' : 'hidden' }}">
                                        <canvas id="sig2-canvas" class="border rounded w-full bg-white" height="120"></canvas>
                                        <div class="flex gap-2 mt-2 text-sm">
                                            <button type="button" class="px-3 py-1 border rounded" onclick="clearCanvas('sig2-canvas')">Clear</button>
                                            <button type="button" class="px-3 py-1 border rounded" onclick="applySignature('sig2')">Gunakan</button>
                                        </div>
                                        <input type="hidden" name="signature2_draw" id="signature2_draw" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stamp -->
                    <div class="pt-2">
                        <h4 class="font-semibold text-gray-900 mb-2">Cap/Stamp (opsional)</h4>
                        <div class="flex items-center space-x-4">
                            <img class="h-12 object-contain" src="{{ $organization->stamp_image ? asset($organization->stamp_image) : 'https://via.placeholder.com/120x48?text=Preview' }}" alt="Stamp">
                            <input type="file" name="stamp_image" accept="image/*" class="text-sm" />
                        </div>
                    </div>

                    <div class="pt-4">
                        <button class="bg-blue-600 text-white px-5 py-2 rounded-lg">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-social').addEventListener('click', function(){
        const list = document.getElementById('socials-list');
        const index = list.children.length;
        const row = document.createElement('div');
        row.className = 'flex gap-2 mt-2';
        row.innerHTML = `
            <input name="socials[${index}][platform]" placeholder="Platform (Instagram)" class="flex-1 border rounded-lg px-3 py-2" />
            <input name="socials[${index}][handle]" placeholder="@username / url" class="flex-1 border rounded-lg px-3 py-2" />
        `;
        list.appendChild(row);
    });

    // Preview logo before upload
    const logoInput = document.getElementById('logo-input');
    const logoPreview = document.getElementById('logo-preview');
    if (logoInput) {
        logoInput.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    logoPreview.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle upload/draw sections
    function toggleSig(slot, type) {
        document.getElementById(slot+'-upload').classList.toggle('hidden', type==='draw');
        document.getElementById(slot+'-draw').classList.toggle('hidden', type!=='draw');
    }

    document.querySelectorAll('input[name="signature1_type"]').forEach(r=>{
        r.addEventListener('change', (e)=> toggleSig('sig1', e.target.value));
    });
    document.querySelectorAll('input[name="signature2_type"]').forEach(r=>{
        r.addEventListener('change', (e)=> toggleSig('sig2', e.target.value));
    });

    // Canvas draw handlers
    function setupCanvas(id){
        const canvas = document.getElementById(id);
        const ctx = canvas.getContext('2d');
        let drawing = false;
        const start = (x,y)=>{ drawing=true; ctx.beginPath(); ctx.moveTo(x,y); };
        const move = (x,y)=>{ if(!drawing) return; ctx.lineTo(x,y); ctx.strokeStyle = '#111'; ctx.lineWidth = 2; ctx.stroke(); };
        const end = ()=>{ drawing=false; };
        canvas.addEventListener('mousedown', e=> start(e.offsetX, e.offsetY));
        canvas.addEventListener('mousemove', e=> move(e.offsetX, e.offsetY));
        window.addEventListener('mouseup', end);
        // touch
        canvas.addEventListener('touchstart', e=>{ const r=canvas.getBoundingClientRect(); const t=e.touches[0]; start(t.clientX-r.left, t.clientY-r.top); });
        canvas.addEventListener('touchmove', e=>{ const r=canvas.getBoundingClientRect(); const t=e.touches[0]; move(t.clientX-r.left, t.clientY-r.top); e.preventDefault(); }, {passive:false});
        window.addEventListener('touchend', end);
    }
    setupCanvas('sig1-canvas');
    setupCanvas('sig2-canvas');

    window.clearCanvas = function(id){
        const c = document.getElementById(id); const ctx=c.getContext('2d'); ctx.clearRect(0,0,c.width,c.height);
    }
    window.applySignature = function(slot){
        const canvas = document.getElementById(slot+'-canvas');
        const dataUrl = canvas.toDataURL('image/png');
        document.getElementById(slot==='sig1'?'signature1_draw':'signature2_draw').value = dataUrl;
        alert('Tanda tangan disimpan dari kanvas. Klik Simpan Profil untuk menyimpan.');
    }
</script>
@endpush
