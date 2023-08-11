<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\User;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('perfil.index');
    }

    public function store(Request $request){

        $request->request->add(['username'=>Str::slug($request->username)]);

        //Validación
        $this->validate(
            $request,
            [
                'username' => [
                    'required','unique:users,username,'.auth()->user()->id,'min:3','max:20',
                    'not_in:twitter,editar-perfil'
                ],
            ]
        );

        
        if($request->imagen){
             //Obtener todos los request

        $imagen=$request->file('imagen');  

        //Generar un nombre único gracias al uuid

        $nombreImagen=Str::uuid() . "." . $imagen->extension();


        //Hacer imagenes intervention Image, el imagenservidor es una instancia de Image
        $imagenServidor=Image::make($imagen);
        //1000px*
        $imagenServidor->fit(1000,1000);

        $imagenPath=public_path('perfiles') . '/' . $nombreImagen;

        $imagenServidor->save($imagenPath);
        }

        //Guardar cambios
        $usuario=User::find(auth()->user()->id);
        $usuario->username=$request->username;
        $usuario->imagen=$nombreImagen ?? auth()->user()->imagen ??null;
        $usuario->save();

        //Redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }
}
