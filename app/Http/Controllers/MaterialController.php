<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'teacher') {
            $materials = Material::with(['class', 'subject'])
                ->where('teacher_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $materials = Material::with(['class', 'subject'])
                ->where('class_id', $user->class_id)
                ->published()
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Statistik untuk dashboard
        $stats = [
            'total' => $materials->count(),
            'documents' => $materials->where('type', 'document')->count(),
            'videos' => $materials->where('type', 'video')->count(),
            'images' => $materials->where('type', 'image')->count(),
            'audio' => $materials->where('type', 'audio')->count(),
            'drafts' => $materials->where('status', 'draft')->count(),
            'published' => $materials->where('status', 'published')->count(),
        ];

        return view('materials.index', compact('materials', 'stats'));
    }

    public function create()
    {
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('materials.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:document,video,image,audio,text',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $data = [
            'title' => $request->title,
            'type' => $request->type,
            'teacher_id' => Auth::id(),
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'description' => $request->description,
            'content' => $request->content,
            'status' => $request->status ?? 'draft',
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materials', $filename, 'public');
            
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getMimeType();

            // Generate thumbnail for images and videos
            if (in_array($request->type, ['image', 'video'])) {
                $thumbnailPath = $this->generateThumbnail($file, $request->type);
                if ($thumbnailPath) {
                    $data['thumbnail_path'] = $thumbnailPath;
                }
            }
        }

        $material = Material::create($data);

        if ($request->status === 'published') {
            return redirect()->route('materials.index')
                ->with('success', 'Materi berhasil dipublikasikan!');
        } else {
            return redirect()->route('materials.index')
                ->with('success', 'Materi berhasil disimpan sebagai draft!');
        }
    }

    public function show(Material $material)
    {
        // Increment views
        $material->incrementViews();
        
        $material->load(['class', 'subject', 'teacher']);
        
        return view('materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('materials.edit', compact('material', 'classes', 'subjects'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:document,video,image,audio,text',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'type' => $request->type,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'description' => $request->description,
            'content' => $request->content,
            'status' => $request->status ?? $material->status,
        ];

        // Handle YouTube URL
        if ($request->youtube_url) {
            $data['youtube_url'] = $request->youtube_url;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            if ($material->thumbnail_path) {
                Storage::disk('public')->delete($material->thumbnail_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materials', $filename, 'public');
            
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getMimeType();

            // Generate thumbnail for images and videos
            if (in_array($request->type, ['image', 'video'])) {
                $thumbnailPath = $this->generateThumbnail($file, $request->type);
                if ($thumbnailPath) {
                    $data['thumbnail_path'] = $thumbnailPath;
                }
            }
        }

        $material->update($data);

        return redirect()->route('materials.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(Material $material)
    {
        // Delete files
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        if ($material->thumbnail_path) {
            Storage::disk('public')->delete($material->thumbnail_path);
        }

        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Materi berhasil dihapus!');
    }

    public function publish(Material $material)
    {
        $material->update(['status' => 'published']);
        
        return back()->with('success', 'Materi berhasil dipublikasikan!');
    }

    public function unpublish(Material $material)
    {
        $material->update(['status' => 'draft']);
        
        return back()->with('success', 'Materi berhasil diubah menjadi draft!');
    }

    private function generateThumbnail($file, $type)
    {
        try {
            $filename = 'thumb_' . time() . '_' . Str::random(10) . '.jpg';
            $thumbnailPath = 'materials/thumbnails/' . $filename;
            
            if ($type === 'image') {
                // Generate thumbnail for image
                $image = \Intervention\Image\Facades\Image::make($file);
                $image->resize(300, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save(storage_path('app/public/' . $thumbnailPath));
            } elseif ($type === 'video') {
                // For video, we'll use a placeholder or extract frame
                // This is a simplified version - you might want to use FFmpeg
                $image = \Intervention\Image\Facades\Image::make(public_path('images/video-placeholder.jpg'));
                $image->resize(300, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save(storage_path('app/public/' . $thumbnailPath));
            }
            
            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
