<?php

namespace Controllers;

use Core;
use Models\Estoque;
use Models\Produtos;
use Models\ImagensProdutos;

class ProdutosController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Produtos";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('produtos/index', []);
    }

    public function create()
    {
        extract($_REQUEST);

        $preco_de_compra = str_replace(",", ".", str_replace(".", "", $preco_de_compra));
        $preco_de_venda = str_replace(",", ".", str_replace(".", "", $preco_de_venda));

        if (isset($_FILES["imagem"]) && strlen($_FILES["imagem"]["name"]) > 0) {
            $imagem = $_FILES["imagem"];
            $result = $this->fileUpload($imagem);
            if ($result != false) {
                $image_id = $result;
            } else {
                return;
            }
        }

        $Produtos = new Produtos();
        $result = $Produtos->create($descricao, $preco_de_venda, $preco_de_compra);

        if ($result != false) {
            (new Estoque)->create($result, 0);

            if (isset($image_id)) {
                (new ImagensProdutos)->create($result, $image_id);
            }
        }

        $this->asJson(["success" => $result]);
    }

    private function fileUpload($file)
    {
        $ds = DIRECTORY_SEPARATOR;
        $storeFolder = '../views' . $ds . 'images';

        $tempFile = $file['tmp_name'];
        $targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;

        if (!file_exists($targetPath)) {
            if (!mkdir($targetPath, 0777, true)) {
                $this->asJson(["success" => false, "message" => "Ocorreu um erro ao criar o diretório de imagens."]);
                return false;
            }
        }

        $newImage = 'img_' . uniqid() . '.jpg';
        $targetFile = $targetPath . $newImage;

        $minDimW = 500;
        $minDimH = 500;

        $maxDimW = 640;
        $maxDimH = 640;

        list($width, $height, $type, $attr) = getimagesize($file['tmp_name']);

        $target_filename = $file['tmp_name'];
        $fn = $file['tmp_name'];
        $imagetype = exif_imagetype(str_replace("jpg", "jpeg", $fn));
        $size = getimagesize($fn);
        $ratio = $size[0] / $size[1];

        if ($imagetype != IMAGETYPE_JPEG) {
            $this->asJson(["success" => false, "message" => "A imagem enviada não está no formato aceito! (.jpeg)"]);
            return false;
        }

        if ($width > $maxDimW || $height > $maxDimH) {
            if ($ratio > 1) {
                $width = $maxDimW;
                $height = $maxDimH / $ratio;
            } else {
                $width = $maxDimW * $ratio;
                $height = $maxDimH;
            }

            $src = imagecreatefromstring(file_get_contents($fn));
            $dst = imagecreatetruecolor($width, $height);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

            imagejpeg($dst, $target_filename, 100);
        } else if ($width < $minDimW || $height < $minDimH) {
            if ($ratio > 1) {
                $width = $minDimW;
                $height = $minDimW / $ratio;
            } else {
                $width = (int) ($minDimH * $ratio);
                $height = $minDimH;
            }

            $src = imagecreatefromstring(file_get_contents($fn));
            $dst = imagecreatetruecolor($width, $height);
            imagecopyresized($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

            imagejpeg($dst, $target_filename, 100);
        }

        if (move_uploaded_file($tempFile, $targetFile)) {
            return $newImage;
        } else {
            $this->asJson(["success" => false, "message" => "Ocorreu um erro ao salvar a imagem!"]);
            return false;
        }
    }

    public function listAll()
    {
        $Produtos = new Produtos();
        $result = $Produtos->selectAll();
        $this->asJson($result);
    }

    public function selectAll()
    {
        extract($_REQUEST);

        $columns = array(
            0 => 'identificador',
            1 => 'descricao',
            2 => 'preco_de_venda',
            3 => 'preco_de_compra',
            4 => '(((preco_de_venda - preco_de_compra) * 100) / preco_de_compra)'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Produtos = new Produtos();
        $selectAll = $Produtos->selectAll();
        $paginatedSearch = $Produtos->paginatedSearch($search, $order, $dir);

        $totalData = count($selectAll);
        if (empty($search)) {
            $totalFiltered = $totalData;
        } else {
            $totalFiltered = count($paginatedSearch);
        }

        $paginatedSearch = array_slice($paginatedSearch, $start, $length);
        $data = array();
        foreach ($paginatedSearch as $outer_key => $array) {
            $nestedData = array();
            foreach ($array as $inner_key => $value) {
                if (!(int) $inner_key) {
                    $nestedData[$inner_key] = $value;
                }
            }
            $data[] = $nestedData;
        }

        $this->asJson([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "records" => $data
        ]);
    }

    public function selectAllAtivosComEstoque()
    {
        extract($_REQUEST);
        $produtosNoCarrinho = $postData ?? [];

        $columns = array(
            0 => 'descricao',
            1 => 'quantidade',
            2 => 'preco_de_venda'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Produtos = new Produtos();
        $selectAll = $Produtos->selectAllAtivosComEstoque($produtosNoCarrinho);
        $paginatedSearch = $Produtos->paginatedSearchAtivosComEstoque($produtosNoCarrinho, $search, $order, $dir, $start, $length);

        $totalData = count($selectAll);

        if (empty($search)) {
            $totalFiltered = $totalData;
        } else {
            $totalFiltered = count($paginatedSearch);
        }

        $data = array();
        if ($paginatedSearch != false) {
            foreach ($paginatedSearch as $outer_key => $array) {
                $nestedData = array();
                foreach ($array as $inner_key => $value) {
                    if (!(int) $inner_key) {
                        $nestedData[$inner_key] = $value;
                    }
                }
                $data[] = $nestedData;
            }
        }

        $this->asJson([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "records" => $data
        ]);
    }

    public function selectAllImages()
    {
        extract($_REQUEST);

        $columns = array(
            0 => 'imagem',
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;
        $produto = $postData["produto"];

        $ImagensProdutos = new ImagensProdutos();
        $selectAll = $ImagensProdutos->selectAll($produto);
        $paginatedSearch = $ImagensProdutos->paginatedSearch($produto, $search, $order, $dir, $start, $length);

        if ($selectAll == false)
            $totalData = 0;
        else
            $totalData = count($selectAll);

        if (empty($search)) {
            $totalFiltered = $totalData;
        } else {
            $totalFiltered = count($paginatedSearch);
        }

        $data = array();
        if ($paginatedSearch != false && is_array($paginatedSearch) == false) {
            $paginatedSearch = [(array) $paginatedSearch];
        }

        if ($paginatedSearch != false) {
            foreach ($paginatedSearch as $outer_key => $array) {
                $nestedData = array();
                foreach ($array as $inner_key => $value) {
                    if (!(int) $inner_key) {
                        $nestedData[$inner_key] = $value;
                    }
                }
                $data[] = $nestedData;
            }
        }

        $this->asJson([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "records" => $data
        ]);
    }

    public function selectById()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->selectById($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function edit()
    {
        extract($_REQUEST);

        $preco_de_compra = str_replace(",", ".", str_replace(".", "", $preco_de_compra));
        $preco_de_venda = str_replace(",", ".", str_replace(".", "", $preco_de_venda));

        $Produtos = new Produtos();
        $result = $Produtos->edit($descricao, $preco_de_venda, $preco_de_compra, $identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function addImage()
    {
        extract($_REQUEST);

        if (isset($_FILES["imagem"])) {
            $imagem = $_FILES["imagem"];
            $result = $this->fileUpload($imagem);
            if ($result != false) {
                $image_id = $result;
            } else {
                return;
            }

            $result = (new ImagensProdutos)->create($produto, $image_id);

            $this->asJson(["success" => $result]);
        } else {
            $this->asJson(["success" => false]);
        }
    }

    public function delete()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->delete($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function deleteImagem()
    {
        extract($_REQUEST);

        $ImagensProdutos = new ImagensProdutos();
        $result = $ImagensProdutos->delete($identificador);

        $targetPath = BASE_PATH . 'views' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $nome;
        unlink($targetPath);

        $this->asJson(["success" => true, "results" => $result]);
    }

    public function searchByText()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->searchByText($searchText ?? "%");

        $this->asJson(["success" => true, "results" => $result]);
    }
}
