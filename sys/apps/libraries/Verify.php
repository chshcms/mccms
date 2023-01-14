<?php
/*
'软件名称：漫城CMS（Mccms）
'官方网站：http://www.mccms.cn/
'软件作者：桂林崇胜网络科技有限公司（By:烟雨江南）
'--------------------------------------------------------
'Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
'遵循Apache2开源协议发布，并提供免费使用。
'--------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Verify验证码类
 */
class Verify {
    private $codeSet   =  '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';// 验证码字符集合
    private $expire    =  1800;            // 验证码过期时间（s）
    private $fontSize  =  14;              // 验证码字体大小(px)
    private $useCurve  =  true;            // 是否画混淆曲线
    private $useNoise  =  false;            // 是否添加杂点	
    private $imageH    =  38;               // 验证码图片高度
    private $imageW    =  100;               // 验证码图片宽度
    private $length    =  4;               // 验证码位数
    private $fontttf   =  '';// 验证码字体
    private $bg        =  array(250, 250, 250);  // 背景颜色
    private $reset     =  true;           // 验证成功后是否重置
    private $_image    = NULL;     // 验证码图片实例
    private $_color    = NULL;     // 验证码字体颜色

    /**
     * 架构方法 设置参数
     * @access public     
     * @param  array $config 配置参数
     */    
    public function __construct($config=array()){
    	$this->CI = &get_instance();
    	foreach ($config as $key => $value) {
    		$this->$key = $value;
    	}
        $this->CI->load->library('session');
    }

    /**
     * 验证验证码是否正确
     * @access public
     * @param string $code 用户验证码
     * @param string $id 验证码标识     
     * @return bool 用户验证码是否正确
     */
    public function check($code) {
        // 验证码不能为空
        if(empty($code) || !isset($_SESSION['verify_time']) || !isset($_SESSION['verify_code'])) {
            return false;
        }
        // session 过期
        if((time() - $_SESSION['verify_time']) > $this->expire) {
            return false;
        }
        //验证码
        if(strtolower($code) == strtolower(sys_auth($_SESSION['verify_code'],1))) {
            if($this->reset) unset($_SESSION['verify_code'],$_SESSION['verify_time']);
            return true;
        }
        return false;
    }

    /**
     * 输出验证码并把验证码的值保存的session中
     * 验证码保存到session的格式为： array('verify_code' => '验证码值', 'verify_time' => '验证码创建时间');
     * @access public     
     * @param string $id 要生成验证码的标识
     * @return void
     */
    public function entry() {

        // 图片宽(px)
        $this->imageW || $this->imageW = $this->length*$this->fontSize*1.5 + $this->length*$this->fontSize/2; 
        // 图片高(px)
        $this->imageH || $this->imageH = $this->fontSize * 2.5;
        // 建立一幅 $this->imageW x $this->imageH 的图像
        $this->_image = imagecreate($this->imageW, $this->imageH); 
        // 设置背景      
        imagecolorallocate($this->_image, $this->bg[0], $this->bg[1], $this->bg[2]); 

        // 验证码字体随机颜色
        $this->_color = imagecolorallocate($this->_image, mt_rand(1,150), mt_rand(1,150), mt_rand(1,150));

        // 验证码使用随机字体
        if(empty($this->fontttf)){
        	$rand = rand(1,6);
            $this->fontttf = FCPATH . 'caches/fonts/'.$rand.'.ttf';
        } 

        if ($this->useNoise) {
            // 绘杂点
            $this->_writeNoise();
        } 
        if ($this->useCurve) {
            // 绘干扰线
            $this->_writeCurve();
        }
        
        // 绘验证码
        $code = array(); // 验证码
        $codeNX = 0; // 验证码第N个字符的左边距
        for ($i = 0; $i<$this->length; $i++) {
            $code[$i] = $this->codeSet[mt_rand(0, strlen($this->codeSet)-1)];
            $codeNX  += mt_rand($this->fontSize*1.2, $this->fontSize*1.6);
            imagettftext($this->_image, $this->fontSize, mt_rand(-40, 40), $codeNX, $this->fontSize*1.6, $this->_color, $this->fontttf, $code[$i]);
        }
       
        // 保存验证码
        $secode     =   array();
        $secode['verify_code'] = sys_auth(implode('',$code)); // 把校验码保存到session
        $secode['verify_time'] = time();  // 验证码创建时间
        $this->CI->session->set_userdata($secode);

        header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);		
        header('Pragma: no-cache');
        header("content-type: image/png");

        // 输出图像
        imagepng($this->_image);
        imagedestroy($this->_image);
    }

    /** 
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数) 
     *      
     *      高中的数学公式咋都忘了涅，写出来
     *		正弦型函数解析式：y=Asin(ωx+φ)+b
     *      各常数值对函数图像的影响：
     *        A：决定峰值（即纵向拉伸压缩的倍数）
     *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     *        ω：决定周期（最小正周期T=2π/∣ω∣）
     *
     */
    private function _writeCurve() {
        $px = $py = 0;
        
        // 曲线前部分
        $A = mt_rand(1, $this->imageH/2);                  // 振幅
        $b = mt_rand(-$this->imageH/4, $this->imageH/4);   // Y轴方向偏移量
        $f = mt_rand(-$this->imageH/4, $this->imageH/4);   // X轴方向偏移量
        $T = mt_rand($this->imageH, $this->imageW*2);  // 周期
        $w = (2* M_PI)/$T;
                        
        $px1 = 0;  // 曲线横坐标起始位置
        $px2 = mt_rand($this->imageW/2, $this->imageW * 0.8);  // 曲线横坐标结束位置

        for ($px=$px1; $px<=$px2; $px = $px + 1) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $this->imageH/2;  // y = Asin(ωx+φ) + b
                $i = (int) ($this->fontSize/5);
                while ($i > 0) {	
                    imagesetpixel($this->_image, $px + $i , $py + $i, $this->_color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多				
                    $i--;
                }
            }
        }
        
        // 曲线后部分
        $A = mt_rand(1, $this->imageH/2);                  // 振幅		
        $f = mt_rand(-$this->imageH/4, $this->imageH/4);   // X轴方向偏移量
        $T = mt_rand($this->imageH, $this->imageW*2);  // 周期
        $w = (2* M_PI)/$T;		
        $b = $py - $A * sin($w*$px + $f) - $this->imageH/2;
        $px1 = $px2;
        $px2 = $this->imageW;

        for ($px=$px1; $px<=$px2; $px=$px+ 1) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $this->imageH/2;  // y = Asin(ωx+φ) + b
                $i = (int) ($this->fontSize/5);
                while ($i > 0) {			
                    imagesetpixel($this->_image, $px + $i, $py + $i, $this->_color);	
                    $i--;
                }
            }
        }
    }

    /**
     * 画杂点
     * 往图片上写不同颜色的字母或数字
     */
    private function _writeNoise() {
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        for($i = 0; $i < 10; $i++){
            //杂点颜色
            $noiseColor = imagecolorallocate($this->_image, mt_rand(150,225), mt_rand(150,225), mt_rand(150,225));
            for($j = 0; $j < 5; $j++) {
                // 绘杂点
                imagestring($this->_image, 5, mt_rand(-10, $this->imageW),  mt_rand(-10, $this->imageH), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }
}