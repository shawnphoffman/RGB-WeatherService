// Color Class
class rgb_color {

  private:
    int my_r;
    int my_g;
    int my_b;
  public:
    rgb_color (int red, int green, int blue)
      :
        my_r(red),
        my_g(green),
        my_b(blue)
    {
    }

    int r() const {return my_r;}
    int b() const {return my_b;}
    int g() const {return my_g;}
};

// Fader Class
class fader {

  private:
    int r_pin;
    int g_pin;
    int b_pin;

  public:
    fader( int red_pin, int green_pin, int blue_pin)  :
        r_pin(red_pin),
        g_pin(green_pin),
        b_pin(blue_pin)
    {
    }

    void fade( const rgb_color& in,
               const rgb_color& out,
               unsigned n_steps = 256,
               unsigned time    = 10)   //wait 10 ms per step
    {
      int red_diff   = out.r() - in.r();
      int green_diff = out.g() - in.g();
      int blue_diff  = out.b() - in.b();
      for ( unsigned i = 0; i < n_steps; ++i){
        rgb_color output ( in.r() + i * red_diff / n_steps,
                           in.g() + i * green_diff / n_steps,
                           in.b() + i * blue_diff/ n_steps);
        analogWrite( r_pin, output.r() );
        analogWrite( g_pin, output.g() );
        analogWrite( b_pin, output.b() );
        delay(time);
      }
    }

};

// Variables
int redPin = A4;
int greenPin = A5;
int bluePin = A6;
rgb_color curColor(0,0,0);
int curRed   = 0;
int curBlue  = 0;
int curGreen = 0;

// Initial Setup
void setup()
{
    pinMode(redPin, OUTPUT);
    pinMode(greenPin, OUTPUT);
    pinMode(bluePin, OUTPUT);

    Spark.function("setColor", setColor);

    Spark.variable("redPin",   &redPin,   INT);
    Spark.variable("greenPin", &greenPin, INT);
    Spark.variable("bluePin",  &bluePin,  INT);
    Spark.variable("curRed",   &curRed,   INT);
    Spark.variable("curBlue",  &curBlue,  INT);
    Spark.variable("curGreen", &curGreen, INT);
}

// Program Loop
void loop()
{
    fader f(redPin, greenPin, bluePin);

    f.fade(curColor, curColor);
}

// Set Color Function
int setColor(String args)
{
    fader f(redPin, greenPin, bluePin);
    curRed   = args.substring(0,3).toInt();
    curGreen = args.substring(3,6).toInt();
    curBlue  = args.substring(6,9).toInt();
    rgb_color newColor(curRed, curGreen, curBlue);
    f.fade(curColor, newColor);
    curColor = newColor;
    return 200;
}

