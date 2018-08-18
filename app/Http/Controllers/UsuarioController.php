<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\HTTP\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Usuario;
use DB;
use Hash;
use GuzzleHttp\Client;


class UsuarioController extends Controller
{
    public function __construct()
    {
      // $this->middleware('auth');
    }
    
     public function clienteget()
    {

    	return view('usuario.estudiantevistaregistrar');
    }

    public function estudianteregistrar(Request $request)
    { 
     $file=Input::file('foto');
    
      $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

      $response = $client->request('POST', 'http://apiro.proyectosuniversitarios.com/public/api/usuarios', [
		    'multipart' => [
		    	[
		            'name'     => 'nombre',
		            'contents' => $request->get('nombre')
		        ],
		        [
		            'name'     => 'email',
		            'contents' => $request->get('email')
		        ],
		        [
		                 'name' => 'foto',
		                 'filename' => $file->getClientOriginalName(),
		                 'contents' => fopen($file->getPathname(), 'r')
		        ],
		        [
		            'name'     => 'password',
		            'contents' => $request->get('password')
		        ]
		    ]
		]);

         
         $respuesta=json_decode($response->getBody()->getContents());
        if($respuesta->status=='success'){
            return redirect('registrarestudiante')->with('msjs',"Se registro correctamente");
        }else{
        	 return redirect('registrarestudiante')->with('msjs',"error al ingresar los datos");
        }
    	
    }


   public function listaestudiantes()
    {

   $client = new \GuzzleHttp\Client(['base_uri' => 'http://apiro.proyectosuniversitarios.com/public/api/']);
   $response = $client->request('GET', 'usuarios');
   $respuesta=json_decode($response->getBody()->getContents());

    	return view('usuario.listaestudiantes',["respuesta"=>$respuesta]);
    }


   public function listachoferes()
    {

   $client = new \GuzzleHttp\Client(['base_uri' => 'http://apiro.proyectosuniversitarios.com/public/api/']);
   $response = $client->request('GET', 'usuarios');
   $respuesta=json_decode($response->getBody()->getContents());


    	return view('usuario.listachoferes',["respuesta"=>$respuesta]);
    }


    public function getregistrarrutas()
    {
       $cliente = new \GuzzleHttp\Client(['base_uri' => 'http://apiro.proyectosuniversitarios.com/public/api/']);
	   $respo = $cliente->request('GET', 'lista_auto');
	   $auto=json_decode($respo->getBody()->getContents());
	  
    	return view('usuario.getregistrarrutas',["auto"=>$auto]);
    }

        public function rutaregistrar(Request $request)
    { 
      $ruta=explode( ',', $request->gps);
      $can=sizeof($ruta);
    
     if($request->gps==null){
         return redirect('registrarruta')->with('msjs',"debe agregar ruta");
      }
      
     $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

      $response = $client->request('POST', 'http://apiro.proyectosuniversitarios.com/public/api/save_servicio', [
		    'multipart' => [
		    	[
		            'name'     => 'costo',
		            'contents' => $request->get('costo')
		        ],
		        [
		            'name'     => 'salida',
		            'contents' => $request->get('salida')
		        ],
		        [
                    'name'     => 'id_auto',
		            'contents' => $request->get('id_auto')
		        ],
		        [
		            'name'     => 'id_chofer',
		            'contents' => Auth::user()->id
		        ]
		    ]
		]);

         
         $respuesta=json_decode($response->getBody()->getContents());
         //dd($respuesta->id);
       if($respuesta->status=='success'){
     
		    for ($i=0; $i < $can ; $i=$i+2) { 
		    
		      $response1 = $client->request('POST', 'http://apiro.proyectosuniversitarios.com/public/api/save_ruta', [
				    'multipart' => [
				    	[
				            'name'     => 'id_servicio',
				            'contents' => $respuesta->id
				        ],
				        [
				            'name'     => 'latitud',
				            'contents' => $ruta[$i]
				        ],
				        [
		                    'name'     => 'longitud',
				            'contents' => $ruta[$i+1]
				        ]
				    ]
				]);
		     }

           return redirect('registrarruta')->with('msjs',"se registro correctamente");
        }else{
        	 return redirect('registrarruta')->with('msjs',"error al ingresar los datos");
        }
    	
    }


}

