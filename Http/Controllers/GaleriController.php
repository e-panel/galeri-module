<?php

namespace Modules\Galeri\Http\Controllers;

use Modules\Core\Http\Controllers\CoreController as Controller;
use Illuminate\Http\Request;

use Modules\Galeri\Entities\Galeri;
use Modules\Galeri\Entities\Album;

class GaleriController extends Controller
{
    protected $title = 'Galeri';

    /**
     * Siapkan konstruktor controller
     * 
     * @param Galeri $data
     */
    public function __construct(Galeri $data, Album $album) 
    {
        $this->data = $data;
        $this->album = $album;

        $this->toIndex = route('epanel.album.galeri.index', request()->segment(4));
        $this->prefix = 'epanel.album';
        $this->view = 'galeri::galeri';

        $this->tCreate = "Data $this->title berhasil ditambah!";
        $this->tUpdate = "Data $this->title berhasil diubah!";
        $this->tDelete = "Beberapa data $this->title berhasil dihapus sekaligus!";

        view()->share([
            'title' => $this->title, 
            'view' => $this->view, 
            'prefix' => $this->prefix
        ]);
    }

    /**
     * Tampilkan halaman utama modul yang dipilih
     * 
     * @param Request $request
     * @return Response|View
     */
    public function index(Request $request, $album) 
    {
        $album = $this->album->uuid($album)->firstOrFail();

        $data = $album->galeri()->latest()->get();

        return view("$this->view.index", compact('data', 'album'));
    }

    /**
     * Tampilkan halaman untuk menambah data
     * 
     * @return Response|View
     */
    public function create($album) 
    {
        $album = $this->album->uuid($album)->firstOrFail();

        return view("$this->view.upload", compact('album'));
    }

    /**
     * Lakukan penyimpanan data ke database
     * 
     * @param Request $request
     * @return Response|View
     */
    public function store(Request $request, $album) 
    {
        $album = $this->album->uuid($album)->firstOrFail();

        if($request->hasFile('file')):

            $data['foto'] = $this->upload($request->file('file'), "{$album->slug}-".str_random(5));
            $data['id_album'] = $album->id;
            $data['id_operator'] = optional(auth()->user())->id;

            $this->data->create($data);
            
            return response()->json(['success' => $data['foto']]);
        endif;
    }

    /**
     * Menampilkan detail lengkap
     * 
     * @param Int $id
     * @return Response|View
     */
    public function show($album, $id)
    {
        return abort(404);
    }

    /**
     * Tampilkan halaman perubahan data
     * 
     * @param Int $id
     * @return Response|View
     */
    public function edit($album, $id)
    {
        return abort(404);
    }

    /**
     * Lakukan perubahan data sesuai dengan data yang diedit
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|View
     */
    public function update(Request $request, $album, $id)
    {
        return abort(404);
    }

    /**
     * Lakukan penghapusan data yang tidak diinginkan
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|String
     */
    public function destroy(Request $request, $album, $id)
    {
        $data = $this->data->uuid($id)->firstOrFail();
        deleteImg($data->foto);
        $data->delete();

        notify()->flash('Foto berhasil dihapus!', 'success');
        return redirect()->back();
    }

    /**
     * Function for Upload File
     * 
     * @param  $file, $filename
     * @return URI
     */
    public function upload($file, $filename) 
    {
        $tmpFilePath = 'app/public/Media/Galeri/';
        $tmpFileDate =  date('Y-m') .'/'.date('d').'/';
        $tmpFileName = $filename;
        $tmpFileExt = $file->getClientOriginalExtension();

        makeImgDirectory($tmpFilePath . $tmpFileDate);
        
        $nama_file = $tmpFilePath . $tmpFileDate . $tmpFileName;
        
        \Image::make($file->getRealPath())->resize(720, null, function($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path() . "/$nama_file.$tmpFileExt");
        
        \Image::make($file->getRealPath())->fit(300, 300)->save(storage_path() . "/{$nama_file}_m.$tmpFileExt");
        \Image::make($file->getRealPath())->fit(100, 100)->save(storage_path() . "/{$nama_file}_s.$tmpFileExt");

        return "storage/Media/Galeri/{$tmpFileDate}{$tmpFileName}.{$tmpFileExt}";
    }
}
