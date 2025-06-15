<!DOCTYPE html>
<html>
<head>
    <title>Manajemen File Terenkripsi</title>
</head>
<body>
    <h2>Upload PDF dan Enkripsi</h2>

    {{-- Pesan sukses / error --}}
    @if(session('success')) 
        <p style="color:green">{{ session('success') }}</p> 
    @endif
    @if(session('error')) 
        <p style="color:red">{{ session('error') }}</p> 
    @endif

    {{-- Form Upload --}}
    <form method="POST" action="{{ route('upload.pdf') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file_pdf" accept="application/pdf" required>
        <button type="submit">Upload & Enkripsi</button>
    </form>

    <br>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ url('/files') }}">
        <input type="text" name="search" placeholder="Cari file..." value="{{ request('search') }}">
        <button type="submit">🔍 Cari</button>
    </form>

    <h3>Daftar File Terenkripsi</h3>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nama Asli</th>
                <th>Nama Terenkripsi</th>
                <th>Waktu Upload</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($files as $file)
                <tr>
                    <td>{{ $file->original_name }}</td>
                    <td>{{ $file->encrypted_name }}</td>
                    <td>{{ $file->created_at }}</td>
                    <td>
                        <a href="{{ route('file.decrypt', $file->id) }}">🔓 Dekripsi</a> |
                        <a href="{{ route('file.download', $file->id) }}">📥 Download</a> |
                        <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada file ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
