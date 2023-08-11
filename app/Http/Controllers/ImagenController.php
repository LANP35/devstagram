<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;

//Importar
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class ImagenController extends Controller
{
    public function store(Request $request)
    {
        //Obtener todos los request

        $imagen=$request->file('file');  

        //Generar un nombre único gracias al uuid

        $nombreImagen=Str::uuid() . "." . $imagen->extension();


        //Hacer imagenes intervention Image, el imagenservidor es una instancia de Image
        $imagenServidor= Image::make($imagen);
        //1000px*
        $imagenServidor->fit(1000,1000);

        $imagenPath=public_path('uploads') . '/' . $nombreImagen;

        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen] );        
    }
}
