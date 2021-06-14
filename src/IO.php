<?php


namespace IsaEken\ComposerUI;


use Composer\IO\BaseIO;
use Composer\IO\IOInterface;
use Illuminate\Support\Str;

class IO extends BaseIO implements IOInterface
{
    /**
     * @param $messages
     * @param bool $newline
     */
    private function wr($messages, bool $newline = true)
    {
        $output = '';

        if (is_array($messages)) {
            foreach ($messages as $message) {
                $output .= sprintf("%s", $message);

                if ($newline) {
                    $output .= "<br>\r\n";
                }
            }
        }
        else {
            $output .= sprintf("%s", $messages);

            if ($newline) {
                $output .= "<br>\r\n";
            }
        }

        $output = Str::of($output)
            ->replace('<info>', '<span style="color: green;">')
            ->replace('<comment>', '<span style="color: yellow">')
            ->replace('<question>', '<span style="color: cyan">')
            ->replace('<error>', '<span style="color: red">')

            ->replace('</info>', '</span>')
            ->replace('</comment>', '</span>')
            ->replace('</question>', '</span>')
            ->replace('</error>', '</span>')
            ->replace('</>', '</span>')
        ;

        echo $output;
    }

    /**
     * @inheritDoc
     */
    public function isInteractive()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isVerbose()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isVeryVerbose()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isDebug()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isDecorated()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function write($messages, $newline = true, $verbosity = self::NORMAL)
    {
        $this->wr($messages, $newline);
    }

    /**
     * @inheritDoc
     */
    public function writeError($messages, $newline = true, $verbosity = self::NORMAL)
    {
        $this->wr($messages, $newline);
    }

    /**
     * @inheritDoc
     */
    public function overwrite($messages, $newline = true, $size = null, $verbosity = self::NORMAL)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function overwriteError($messages, $newline = true, $size = null, $verbosity = self::NORMAL)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function ask($question, $default = null)
    {
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function askConfirmation($question, $default = true)
    {
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function askAndValidate($question, $validator, $attempts = null, $default = null)
    {
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function askAndHideAnswer($question)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function select($question, $choices, $default, $attempts = false, $errorMessage = 'Value "%s" is invalid', $multiselect = false)
    {
        return $default;
    }
}
