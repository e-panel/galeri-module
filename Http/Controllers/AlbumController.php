<?php

namespace Modules\Galeri\Http\Controllers;

use Modules\Core\Http\Controllers\CoreController as Controller;
use Illuminate\Http\Request;

use Modules\Galeri\Entities\Album;

class AlbumController extends Controller
{
    protected $title = 'Album';

    /**
     * Siapkan konstruktor controller
     * 
     * @param Album $data
     */
    public function __construct(Album $data) 
    {
        $this->data = $data;

        $this->toIndex = route('epanel.album.index');
        $this->prefix = 'epanel.album';
        $this->view = 'galeri::album';

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
    public function index(Request $request) 
    {
        $data = $this->data->latest()->get();

        if($request->has('datatable')):
            return $this->datatable($data);
        endif;

        return view("$this->view.index", compact('data'));
    }

    /**
     * Tampilkan halaman untuk menambah data
     * 
     * @return Response|View
     */
    public function create() 
    {
        return view("$this->view.create");
    }

    /**
     * Lakukan penyimpanan data ke database
     * 
     * @param Request $request
     * @return Response|View
     */
    public function store(Request $request) 
    {
        $this->validate($request, [
            'judul' => 'required', 
            'tgl_terbit' => 'required', 
        ]);

        $input = $request->all();

        if($request->filled('tgl_terbit')):
            $input['created_at'] = date('Y-m-d H:i:s', strtotime($request->tgl_terbit));
        else:
            $input['created_at'] = \Carbon\Carbon::now();
        endif;
        
        $this->data->create($input);
        
        notify()->flash($this->tCreate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Menampilkan detail lengkap
     * 
     * @param Int $id
     * @return Response|View
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Tampilkan halaman perubahan data
     * 
     * @param Int $id
     * @return Response|View
     */
    public function edit($id)
    {
        $edit = $this->data->uuid($id)->firstOrFail();
    
        return view("$this->view.edit", compact('edit'));
    }

    /**
     * Lakukan perubahan data sesuai dengan data yang diedit
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|View
     */
    public function update(Request $request, $id)
    {
        $edit = $this->data->uuid($id)->firstOrFail();

        $this->validate($request, [
            'judul' => 'required', 
            'tgl_terbit' => 'required', 
        ]);

        $input = $request->all();

        if($request->filled('tgl_terbit')):
            $input['created_at'] = date('Y-m-d H:i:s', strtotime($request->tgl_terbit));
        else:
            $input['created_at'] = \Carbon\Carbon::now();
        endif;
        
        $edit->update($input);
        
        notify()->flash($this->tUpdate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Lakukan penghapusan data yang tidak diinginkan
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|String
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('pilihan')):

            foreach($request->pilihan as $temp):
                $data = $this->data->uuid($temp)->firstOrFail();
                foreach($data->galeri as $side):
                    deleteImg($side->foto);
                    $side->delete();
                endforeach;
                $data->delete();
            endforeach;
            
            notify()->flash($this->tDelete, 'success');
            return redirect()->back();
        endif;
    }

    /**
     * Datatable API
     * 
     * @param  $data
     * @return Datatable
     */
    public function datatable($data) 
    {
        return datatables()->of($data)
            ->editColumn('pilihan', function($data) {
                $return  = '<span>';
                $return .= '    <div class="checkbox checkbox-only">';
                $return .= '        <input type="checkbox" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->uuid.'">';
                $return .= '        <label for="pilihan['.$data->id.']"></label>';
                $return .= '    </div>';
                $return .= '</span>';
                return $return;
            })
            ->editColumn('label', function($data) {
                $return  = $data->judul;
                return $return;
            })
            ->editColumn('total', function($data) {
                $return = $data->galeri->count() . ' Photo';
                return $return;
            })
            ->editColumn('created', function($data) {
                \Carbon\Carbon::setLocale('id');
                $return  = '<small>' . date('Y-m-d h:i:s', strtotime($data->created_at)) . '</small><br/>';
                $return .= str_replace('yang ', '', $data->created_at->diffForHumans());
                return $return;
            })
            ->editColumn('aksi', function($data) {
                $return  = '<div class="btn-toolbar">';
                $return .= '    <div class="btn-group btn-group-sm">';
                $return .= '        <a href="' . route("$this->prefix.galeri.index", $data->uuid) . '" class="btn btn-sm btn-success-outline">';
                $return .= '            <span class="fa fa-picture-o"></span> KELOLA';
                $return .= '        </a><a href="'. route("$this->prefix.edit", $data->uuid) .'" role="button" class="btn btn-sm btn-primary-outline">';
                $return .= '            <span class="fa fa-pencil"></span>';
                $return .= '        </a>';
                $return .= '    </div>';
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['pilihan', 'label', 'total', 'created', 'aksi'])->toJson();
    }
}
