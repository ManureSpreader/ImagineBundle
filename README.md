# Introduction

Bundle that will provide image manipulation functionality to your Symfony 2 based projects

# Usage

Define your processors in the yaml configuration for your application like so:

    imagine.imagine:
        thumbnail:
            resize:	[50, true]
            crop:	[0, 0, 50, 50]
            save:	'/uploads/thumbs/'

The above declaration will tell ImagineBundle to prepare image processor 'thumbnail',
that will resize images to 50px width, constraining proportions, and then crop the
bottom to 50px height, and resave the image in '/uploads/thumbs' directory.

> For more information on Imagine library and available commands,
> [check out its git repository at](http://github.com/avalanche123/Imagine)

To use the processor in the application, you would do something like:

    //...
    $processor = $this->container->getService('imagine.processor.thumbnail');
    //or...
    $processor = $this->container->getImagineManagerService()->getProcessor('thumbnail');

And the usual:

    $processor->process(new Imagine\Standard\Image('/uploads/image.jpg'));

Happy Coding!